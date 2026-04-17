import java.util.*;
import java.util.stream.Collectors;

/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/
class Solution {

    public static void main(String args[]) {
        Scanner in = new Scanner(System.in);
        long N = in.nextLong();
        List<Long> fibonacciSuit = fibonacciSuit(N), zecks = new ArrayList<>();
        for (long c; N > 0;)
            if (N >= (c = fibonacciSuit.remove(fibonacciSuit.size() - 1)) && zecks.add(c))
                N -= c;
        System.out.println(zecks.stream().map(l -> l.toString())
                .collect(Collectors.joining("+")));
    }

    private static List<Long> fibonacciSuit(long n)
    {
        List<Long> suit = new ArrayList<>();
        Long[] fBase = {1L, 2L};
        suit.addAll(List.of(fBase));

        for (int i = 0; i < 89 && fBase[0] + fBase[1] < n; i++) {
            suit.add(fBase[i % 2] = fBase[0] + fBase[1]);
        }

        return suit;
    }
}