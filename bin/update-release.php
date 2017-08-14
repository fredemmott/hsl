<?hh
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace HH\Lib\_Private;

use namespace HH\Lib\{C, Dict, Str, Vec};

require_once(__DIR__.'/../vendor/hh_autoload.php');
require_once(__DIR__.'/generate-docs.php');

final class UpdateReleaseScript {
  const type TCommitInfo = shape(
    'id' => string,
    'subject' => string,
    'timestamp' => int,
    'author' => string,
  );

  <<__Memoize>>
  private function getLatestAndNextTags(): (string, string) {
    $all_tags = shell_exec('git tag -l')
      |> Str\trim($$)
      |> explode("\n", $$) // Not using Str\split because of hhvm/hsl#3
      |> vec($$);

    $unexpected_tag = C\find(
      $all_tags,
      $tag ==> preg_match("/^v0\.\d+$/", $tag) !== 1,
    );
    invariant(
      $unexpected_tag === null,
      'Expected all tags to be v0.x but found tag "%s"',
      $unexpected_tag,
    );

    $latest_version = $all_tags
      |> Vec\map($$, $tag ==> (int) explode('.', $tag)[1])
      |> Vec\sort($$)
      |> C\lastx($$);

    return tuple(
      'v0.'.$latest_version,
      'v0.'.($latest_version + 1),
    );
  }

  <<__Memoize>>
  private function getCommitsBetween(
    string $since,
    string $until,
  ): vec<self::TCommitInfo> {
    $head = (string $i): string ==> explode(' ', $i)[0];
    $tail = (string $i): string ==> explode(' ', $i)
      |> Vec\drop($$, 1)
      |> implode(' ', $$);

    $get_feature_dict = $placeholder==>
      sprintf(
        'git log %s %s',
        escapeshellarg('--pretty=format:%H '.$placeholder),
        escapeshellarg($since.'..'.$until),
      )
      |> shell_exec($$)
      |> Str\trim($$)
      |> explode("\n", $$)
      |> Dict\pull(
        $$,
        $line ==> $tail($line),
        $line ==> $head($line),
      );

    $subjects = $get_feature_dict('%s');
    if (C\is_empty($subjects)) {
      return vec[];
    }
    $timestamps = $get_feature_dict('%ct')
      |> Dict\map($$, $ts ==> (int) $ts);
    $authors = $get_feature_dict('%an');

    return Vec\map_with_key(
      $subjects,
      ($id, $subject) ==> shape(
        'id' => $id,
        'subject' => $subject,
        'timestamp' => $timestamps[$id],
        'author' => $authors[$id],
      ),
    );
  }

  <<__Memoize>>
  private function getResolvedCommitish(string $commitish): string {
    return Str\trim(shell_exec('git rev-parse '.escapeshellarg($commitish)));
  }

  private function prettyPrintCommit(self::TCommitInfo $c): string {
    return sprintf(
      ' - [https://github.com/hhvm/hsl/commit/%s](%s) by %s on %s',
      $c['id'],
      $c['subject'],
      $c['author'],
      strftime('%Y-%m-%d', $c['timestamp']),
    );
  }

  private function publishRelease(): void {
    list($latest_tag, $next_tag) = $this->getLatestAndNextTags();
    $head_id = $this->getResolvedCommitish('HEAD');

    $commits = $this->getCommitsBetween($latest_tag, $head_id);
    invariant(
      !C\is_empty($commits),
      'No commits for release; %s should not have been called',
      __FUNCTION__,
    );

    // https://developer.github.com/v3/repos/releases/#create-a-release
    $release_info = shape(
      'tag_name' => $next_tag,
      'target_commitish' => $head_id,
      'name' => $next_tag.': scheduled release',
      'draft' => false,
      'prerelease' => true,
      'body' =>
        Vec\map($commits, $c ==> $this->prettyPrintCommit($c))
        |> implode("\n", $$),
    );
    $json = json_encode($release_info);

    $headers = dict[
      'Authorization' => 'token '.$this->getGitHubAPIKeyOrExit(),
      'Content-Type' => 'application/vnd.github.v3+json',
      'Content-Length' => strlen($json),
    ];

    $ch = curl_init('https://api.github.com/repos/hhvm/hsl/releases');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Facebook/HHVM-HSL-Bot');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

    $result = curl_exec($ch);
    if ($result === true) {
      return;
    }

    // Don't output handy debugging stuff in case it includes the token, as it
    // appears on travis-ci.org
    fprintf(STDERR, "Call to github releases API failed\n");
    exit(1);
  }

  private function updateDocumentation(): void {
    $token = $this->getGitHubAPIKeyOrExit();

    $orig_umask = umask(0077);
    $orig_cwd = getcwd();

    $git_dir = sys_get_temp_dir().'/hhvm-hsl-docs-'.bin2hex(random_bytes(8));
    shell_exec(
      sprintf(
        'git clone --branch gh-pages %s %s',
        escapeshellarg(sprintf('https://%s@github.com/hhvm/hsl.git', $token)),
        escapeshellarg($git_dir),
      ),
    );
    shell_exec(
      'cd '.escapeshellarg($git_dir).' && git rm -rf api*',
    );
    mkdir($git_dir.'/api');
    (new DocsGen())->createInDirectory($git_dir.'/api/');

    chdir($git_dir);
    shell_exec('git add api/');

    $_output = [];
    $exit_code = -1;
    exec('git diff --exit-code', $_output, $exit_code);
    if ($exit_code === 0) {
      print("No changes to documentation.\n");
      return;
    }

    print("Pushing updated documentation.\n");

    shell_exec('git config user.name '.escapeshellarg('HHVM-HSL Bot'));
    shell_exec('git config user.email '.escapeshellarg('31013554+hhvm-hsl-bot@users.noreply.github.com'));
    list($_, $next_release) = $this->getLatestAndNextTags();
    shell_exec('git commit -m '.escapeshellarg('Automated update for '.$next_release));

    $exit_code = -1;
    exec('git push', $_output, $exit_code);
    chdir($orig_cwd);
    umask($orig_umask);

    if ($exit_code !== 0) {
      fprintf(STDERR, "Failed to push documentation to github\n");
      exit(1);
    }
  }

  public function main(): void {
    list($latest_tag, $_) = $this->getLatestAndNextTags();
    $latest_id = $this->getResolvedCommitish($latest_tag);
    $head_id = $this->getResolvedCommitish('HEAD');
    if ($latest_id === $head_id) {
      printf("No commits since %s, so nothing to do.\n", $latest_tag);
      return;
    }

    $this->updateDocumentation();
    $this->publishRelease();
  }

  <<__Memoize>>
  private function getGitHubAPIKeyOrExit(): string {
    $key = getenv('GITHUB_API_KEY');
    if (is_string($key)) {
      return $key;
    }
    fprintf(
      STDERR,
      "Please set the GITHUB_API_KEY environment variable\n",
    );
    exit(1);
  }
}

(new UpdateReleaseScript())->main();
