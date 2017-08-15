# HH\Lib\Vec

 - [Vec\chunk](#vecchunk)
 - [Vec\concat](#vecconcat)
 - [Vec\diff](#vecdiff)
 - [Vec\diff_by](#vecdiff_by)
 - [Vec\drop](#vecdrop)
 - [Vec\fill](#vecfill)
 - [Vec\filter](#vecfilter)
 - [Vec\filter_nulls](#vecfilter_nulls)
 - [Vec\flatten](#vecflatten)
 - [Vec\intersect](#vecintersect)
 - [Vec\keys](#veckeys)
 - [Vec\keys_with_truthy_values](#veckeys_with_truthy_values)
 - [Vec\map](#vecmap)
 - [Vec\map_with_key](#vecmap_with_key)
 - [Vec\partition](#vecpartition)
 - [Vec\range](#vecrange)
 - [Vec\reverse](#vecreverse)
 - [Vec\sample](#vecsample)
 - [Vec\shuffle](#vecshuffle)
 - [Vec\slice](#vecslice)
 - [Vec\sort](#vecsort)
 - [Vec\sort_by](#vecsort_by)
 - [Vec\take](#vectake)
 - [Vec\unique](#vecunique)
 - [Vec\unique_by](#vecunique_by)

## Vec\chunk()

```Hack
function chunk<Tv>(Traversable<Tv> $traversable, int $size): vec<vec<Tv>>
```

Returns a vec containing the original vec split into chunks of the given
size. If the original vec doesn't divide evenly, the final chunk will be
smaller.

## Vec\concat()

```Hack
function concat<Tv>(Traversable<Tv> ...$traversables): vec<Tv>
```

Returns a new vec formed by concatenating the given Traversables together.

For a variable number of Traversables, see Vec\flatten.

## Vec\diff()

```Hack
function diff<Tv1 as arraykey, Tv2 as arraykey>(
  Traversable<Tv1> $first,
  Traversable<Tv2> $second,
  Traversable<Tv2> ...$rest
): vec<Tv1>
```

Returns a new vec containing only the elements of the first Traversable that
do not appear in any of the other ones.

For vecs that contain non-arraykey elements, see Vec\diff_by.

## Vec\diff_by()

```Hack
function diff_by<Tv, Ts as arraykey>(
  Traversable<Tv> $first,
  Traversable<Tv> $second,
  (function(Tv):Ts) $scalar_func,
): vec<Tv>
```

Returns a new vec containing only the elements of the first Traversable
that do not appear in the second one, where an element's identity is
determined by the scalar function.

For vecs that contain arraykey elements, see Vec\diff.

## Vec\drop()

```Hack
function drop<Tv>(Traversable<Tv> $traversable, int $n): vec<Tv>
```

Returns a new vec containing all except the first `$n` elements of the
given Traversable.

To take only the first `$n` elements, see `Vec\take`.

## Vec\fill()

```Hack
function fill<Tv>(int $size, Tv $value): vec<Tv>
```

Returns a new vec of size `$size` where all the values are `$value`.

## Vec\filter()

```Hack
function filter<Tv>(
  Traversable<Tv> $traversable,
  ?(function(Tv):bool) $value_predicate = null,
): vec<Tv>
```

Returns a new vec containing only the values for which the given predicate
returns `true`. The default predicate is casting the value to boolean.

To remove null values in a typechecker-visible way, see Vec\filter_nulls.
To use an async predicate, see Vec\gen_filter.

## Vec\filter_nulls()

```Hack
function filter_nulls<Tv>(Traversable<?Tv> $traversable): vec<Tv>
```

Returns a new vec containing only non-null values of the given
Traversable.

## Vec\flatten()

```Hack
function flatten<Tv>(Traversable<Traversable<Tv>> $traversables): vec<Tv>
```

Returns a new vec formed by joining the Traversable elements of the given
Traversable.

For a fixed number of Traversables, see Vec\concat.

## Vec\intersect()

```Hack
function intersect<Tv as arraykey>(
  Traversable<Tv> $first,
  Traversable<Tv> $second,
  Traversable<Tv> ...$rest
): vec<Tv>
```

Returns a new vec containing only the elements of the first Traversable that
appear in all the other ones. Duplicate values are preserved.

## Vec\keys()

```Hack
function keys<Tk, Tv>(KeyedTraversable<Tk,Tv> $traversable): vec<Tk>
```

Returns a new vec containing the keys of the given KeyedTraversable.

## Vec\keys_with_truthy_values()

```Hack
function keys_with_truthy_values<Tk, Tv>(
  KeyedTraversable<Tk,Tv> $traversable,
): vec<Tk>
```

Returns a new vec containing only the keys of the given KeyedTraversable
that map to truthy values.

## Vec\map()

```Hack
function map<Tv1, Tv2>(
  Traversable<Tv1> $traversable,
  (function(Tv1):Tv2) $value_func,
): vec<Tv2>
```

Returns a new vec where each value is the result of calling the given
function on the original value.

For async functions, see Vec\gen_map.

## Vec\map_with_key()

```Hack
function map_with_key<Tk, Tv1, Tv2>(
  KeyedTraversable<Tk,Tv1> $traversable,
  (function(Tk,Tv1):Tv2) $value_func,
): vec<Tv2>
```

Returns a new vec where each value is the result of calling the given
function on the original key and value.

## Vec\partition()

```Hack
function partition<Tv>(
  Traversable<Tv> $traversable,
  (function(Tv):bool) $predicate,
): (vec<Tv>,vec<Tv>)
```

Returns a 2-tuple containing vecs for which the given predicate returned
`true` and `false`, respectively.

## Vec\range()

```Hack
function range<Tv as num>(Tv $start, Tv $end, ?Tv $step = null): vec<Tv>
```

Returns a new vec containing the range of numbers from `$start` to `$end`
inclusive, with the step between elements being `$step` if provided, or 1 by
default.

## Vec\reverse()

```Hack
function reverse<Tv>(Traversable<Tv> $traversable): vec<Tv>
```

Returns a new vec with the values of the given Traversable in reversed
order.

## Vec\sample()

```Hack
function sample<Tv>(Traversable<Tv> $traversable, int $sample_size): vec<Tv>
```

Returns a new vec containing an unbiased random sample of up to
`$sample_size` elements (fewer iff `$sample_size` is larger than the size of
`$traversable`).

## Vec\shuffle()

```Hack
function shuffle<Tv>(Traversable<Tv> $traversable): vec<Tv>
```

Returns a new vec with the values of the given Traversable in a random
order.

## Vec\slice()

```Hack
function slice<Tv>(
  Container<Tv> $container,
  int $offset,
  ?int $length = null,
): vec<Tv>
```

Returns a new vec containing the subsequence of the given Traversable
determined by the offset and length.

If no length is given or it exceeds the upper bound of the Traversable,
the vec will contain every element after the offset.

To take only the first `$n` elements, see `Vec\take`.
To drop the first `$n` elements, see `Vec\drop`.

## Vec\sort()

```Hack
function sort<Tv>(
  Traversable<Tv> $traversable,
  ?(function(Tv,Tv):int) $comparator = null,
): vec<Tv>
```

Returns a new vec sorted by the values of the given Traversable. If the
optional comparator function isn't provided, the values will be sorted in
ascending order.

To sort by some computable property of each value, see Vec\sort_by().

## Vec\sort_by()

```Hack
function sort_by<Tv, Ts>(
  Traversable<Tv> $traversable,
  (function(Tv):Ts) $scalar_func,
  ?(function(Ts,Ts):int) $comparator = null,
): vec<Tv>
```

Returns a new vec sorted by some scalar property of each value of the given
Traversable, which is computed by the given function. If the optional
comparator function isn't provided, the values will be sorted in ascending
order of scalar key.

## Vec\take()

```Hack
function take<Tv>(Traversable<Tv> $traversable, int $n): vec<Tv>
```

Returns a new vec containing the first `$n` elements of the given
Traversable.

To drop the first `$n` elements, see `Vec\drop`.

## Vec\unique()

```Hack
function unique<Tv as arraykey>(Traversable<Tv> $traversable): vec<Tv>
```

Returns a new vec containing each element of the given Traversable exactly
once. The Traversable must contain arraykey values, and strict equality will
be used.

For non-arraykey elements, see Vec\unique_by.

## Vec\unique_by()

```Hack
function unique_by<Tv, Ts as arraykey>(
  Traversable<Tv> $traversable,
  (function(Tv):Ts) $scalar_func,
): vec<Tv>
```

Returns a new vec containing each element of the given Traversable exactly
once, where uniqueness is determined by calling the given scalar function on
the values. In case of duplicate scalar keys, later values will overwrite
previous ones.

For arraykey elements, see Vec\unique.
