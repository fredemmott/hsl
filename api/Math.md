# HH\Lib\Math

 - [Math\abs](#mathabs)
 - [Math\base_convert](#mathbase_convert)
 - [Math\ceil](#mathceil)
 - [Math\cos](#mathcos)
 - [Math\exp](#mathexp)
 - [Math\floor](#mathfloor)
 - [Math\from_base](#mathfrom_base)
 - [Math\int_div](#mathint_div)
 - [Math\log](#mathlog)
 - [Math\max](#mathmax)
 - [Math\mean](#mathmean)
 - [Math\median](#mathmedian)
 - [Math\min](#mathmin)
 - [Math\round](#mathround)
 - [Math\sin](#mathsin)
 - [Math\sqrt](#mathsqrt)
 - [Math\tan](#mathtan)
 - [Math\to_base](#mathto_base)

## Math\abs()

```Hack
function abs<T as num>(T $number): T
```

Returns the absolute value of `$number` (`$number` if `$number` > 0,
`-$number` if `$number` < 0).

## Math\base_convert()

```Hack
function base_convert(string $value, int $from_base, int $to_base): string
```

Converts the given string in base `$from_base` to base `$to_base`, assuming
letters a-z are used for digits for bases greater than 10. The conversion is
done to arbitrary precision.

To convert a string in some base to an int, see `Math\from_base`.
To convert an int to a string in some base, see `Math\to_base`.

## Math\ceil()

```Hack
function ceil(num $value): float
```

Returns the smallest integer value greater than or equal to $value.

To find the largest integer value less than or equal to `$value`, see
`Math\floor`.

## Math\cos()

```Hack
function cos(num $arg): float
```

Returns the cosine of `$arg`.

To find the sine, see `Math\sin`.
To find the tangent, see `Math\tan`.

## Math\exp()

```Hack
function exp(num $arg): float
```

Returns e to the power `$arg`.

To find the logarithm, see `Math\log`.

## Math\floor()

```Hack
function floor(num $value): float
```

Returns the largest integer value less than or equal to `$value`.

To find the smallest integer value greater than or equal to `$value`, see
`Math\ceil`.
To find the largest integer value less than or equal to a ratio, see
`Math\int_div`.

## Math\from_base()

```Hack
function from_base(string $number, int $from_base): int
```

Converts the given string in the given base to an int, assuming letters a-z
are used for digits when `$from_base` > 10.

To base convert an int into a string, see `Math\to_base`.

## Math\int_div()

```Hack
function int_div(int $numerator, int $denominator): int
```

Returns the result of integer division of `$numerator` by `$denominator`.

To round a single value, see `Math\floor`.

## Math\log()

```Hack
function log(num $arg, ?num $base = null): float
```

Returns the logarithm base `$base` of `$arg`.

For the exponential function, see `Math\exp`.

## Math\max()

```Hack
function max<T as num>(T $first_number, T ...$numbers): T
```

Returns the largest of all input numbers.

For finding the smallest number, see Math\min.
For Traversables, see C\max.

## Math\mean()

```Hack
function mean(Container<num> $numbers): ?float
```

Returns the arithmetic mean of the numbers in the given container.

To find the sum, see `C\sum`.
To find the maximum, see `Math\max`.
To find the minimum, see `Math\min`.

## Math\median()

```Hack
function median(Container<num> $numbers): ?float
```

Returns the median of the given numbers.

To find the mean, see `Math\mean`.
To find the standard deviation, see `Math\fb\std_dev`.

## Math\min()

```Hack
function min<T as num>(T $first_number, T ...$numbers): T
```

Returns the smallest of all input numbers.

For finding the largest number, see Math\max.
For Traversables, see C\min.

## Math\round()

```Hack
function round(num $val, int $precision = 0): float
```

Returns the given number rounded to the specified precision. A positive
precision rounds to the nearest decimal place whereas a negative precision
rounds to the nearest power of ten. For example, a precision of 1 rounds to
the nearest tenth whereas a precision of -1 rounds to the nearest ten.

## Math\sin()

```Hack
function sin(num $arg): float
```

Returns the sine of $arg.

To find the cosine, see `Math\cos`.
To find the tangent, see `Math\tan`.

## Math\sqrt()

```Hack
function sqrt(num $arg): float
```

Returns the square root of `$arg`.

## Math\tan()

```Hack
function tan(num $arg): float
```

Returns the tangent of `$arg`.

To find the cosine, see `Math\cos`.
To find the sine, see `Math\sin`.

## Math\to_base()

```Hack
function to_base(int $number, int $to_base): string
```

Converts the given non-negative number into the given base, using letters a-z
for digits when `$to_base` > 10.

To base convert a string to an int, see `Math\from_base`.
