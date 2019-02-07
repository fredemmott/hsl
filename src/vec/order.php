<?hh // strict
/*
 *  Copyright (c) 2004-present, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the MIT license found in the
 *  LICENSE file in the root directory of this source tree.
 *
 */

namespace HH\Lib\Vec;

use namespace HH\Lib\{C, Dict, Math, Str};

/**
 * Returns a new vec containing the range of numbers from `$start` to `$end`
 * inclusive, with the step between elements being `$step` if provided, or 1 by
 * default. If `$start > $end`, it returns a descending range instead of
 * an empty one.
 */
<<__Rx>>
function range<Tv as num>(
  Tv $start,
  Tv $end,
  ?Tv $step = null,
): vec<Tv> {
  $step ??= 1;
  invariant($step > 0, 'Expected positive step.');
  if ($step > Math\abs($end - $start)) {
    return vec[$start];
  }
  /* HH_IGNORE_ERROR[2049] __PHPStdLib */
  /* HH_IGNORE_ERROR[4107] __PHPStdLib */
  return vec(\range($start, $end, $step));
}

/**
 * Returns a new vec with the values of the given Traversable in reversed
 * order.
 */
<<__Rx, __AtMostRxAsArgs>>
function reverse<Tv>(
  <<__MaybeMutable, __OnlyRxIfImpl(\HH\Rx\Traversable::class)>>
  Traversable<Tv> $traversable,
): vec<Tv> {
  $vec = vec($traversable);
  $lo = 0;
  $hi = C\count($vec) - 1;
  while ($lo < $hi) {
    $temp = $vec[$lo];
    $vec[$lo++] = $vec[$hi];
    $vec[$hi--] = $temp;
  }
  return $vec;
}

/**
 * Returns a new vec with the values of the given Traversable in a random
 * order.
 */
function shuffle<Tv>(
  Traversable<Tv> $traversable,
): vec<Tv> {
  $vec = vec($traversable);
  /* HH_FIXME[2049] calling stdlib directly */
  /* HH_FIXME[4107] calling stdlib directly */
  \shuffle(&$vec);
  return $vec;
}

/**
 * Returns a new vec sorted by the values of the given Traversable. If the
 * optional comparator function isn't provided, the values will be sorted in
 * ascending order.
 *
 * To sort by some computable property of each value, see `Vec\sort_by()`.
 */
<<__Rx, __AtMostRxAsArgs>>
function sort<Tv>(
  <<__MaybeMutable, __OnlyRxIfImpl(\HH\Rx\Traversable::class)>>
  Traversable<Tv> $traversable,
  <<__AtMostRxAsFunc>>
  ?(function(Tv, Tv): int) $comparator = null,
): vec<Tv> {
  $vec = vec($traversable);
  if ($comparator) {
    /* HH_FIXME[2088] No refs in reactive code. */
    /* HH_FIXME[2049] calling stdlib directly */
    /* HH_FIXME[4107] calling stdlib directly */
  /* HH_FIXME[4200] rx => non-rx */
    \usort(&$vec, $comparator);
  } else {
    /* HH_FIXME[2088] No refs in reactive code. */
    /* HH_FIXME[2049] calling stdlib directly */
    /* HH_FIXME[4107] calling stdlib directly */
  /* HH_FIXME[4200] rx => non-rx */
    \sort(&$vec);
  }
  return $vec;
}

/**
 * Returns a new vec sorted by some scalar property of each value of the given
 * Traversable, which is computed by the given function. If the optional
 * comparator function isn't provided, the values will be sorted in ascending
 * order of scalar key.
 *
 * To sort by the values of the Traversable, see `Vec\sort()`.
 */
<<__Rx, __AtMostRxAsArgs>>
function sort_by<Tv, Ts>(
  <<__MaybeMutable, __OnlyRxIfImpl(\HH\Rx\Traversable::class)>>
  Traversable<Tv> $traversable,
  <<__AtMostRxAsFunc>>
  (function(Tv): Ts) $scalar_func,
  <<__AtMostRxAsFunc>>
  ?(function(Ts, Ts): int) $comparator = null,
): vec<Tv> {
  $vec = vec($traversable);
  $order_by = Dict\map($vec, $scalar_func);
  if ($comparator) {
    /* HH_FIXME[2088] No refs in reactive code. */
    /* HH_FIXME[2049] calling stdlib directly */
    /* HH_FIXME[4107] calling stdlib directly */
    /* HH_FIXME[4200] rx => non-rx */
    \uasort(&$order_by, $comparator);
  } else {
    /* HH_FIXME[2088] No refs in reactive code. */
    /* HH_FIXME[2049] calling stdlib directly */
    /* HH_FIXME[4107] calling stdlib directly */
    /* HH_FIXME[4200] rx => non-rx */
    \asort(&$order_by);
  }
  return map_with_key($order_by, ($k, $v) ==> $vec[$k]);
}
