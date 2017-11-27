# so-47261385

Solves the question asked here: https://stackoverflow.com/q/47261385

> 1. Input array contains set of arrays.
> 2. Each inner array contains unique elements.
> 3. Each inner array may have different length and different values.
> 4. Output array must contain exact same values.
> 5. Output inner array must have unique values on same key.
> 6. If there is no solution, wildcard  ie.: null are allowed.
> 7. Wildcards can be duplicated on same key.
> 8. Solution should have as few wildcards as possible.
> 9. Algorithm should be able to handle array up to 30x30 in less than 180 s.

---

The idea is to identify conflicting elements and swap them to a column where they are no longer a problem. For cases where this is not applicable a random selection is done. The code works recursive and thus there might be an edge-case where it takes very long to complete.

For a examples check solve.php.

---

Some commands to try:

    > php solve.php solve unique --size=10 --num=3
    > php solve.php solve random --size=10 --num=3
    > php solve.php solve fixed --num=21
