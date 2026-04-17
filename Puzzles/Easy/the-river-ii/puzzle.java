import java.util.*;
import java.io.*;
import java.math.*;

class Solution {
    static boolean eqt[];
    static boolean used[];

    public static void main(String args[]) {
        Scanner in = new Scanner(System.in);
        int r1 = in.nextInt();
        eqt = new boolean[r1];
        used = new boolean[r1];
        int count = 0;
        for (int cnt = 1; cnt < r1; cnt++) {
            if (willReach(cnt,r1)) count++;
            if (count>=2) {System.out.println("YES"); return;}
        }

        System.out.println("NO");
    }

    public static boolean willReach(int cnt,int TARGET) {
        if (used[cnt]) return eqt[cnt];
        used[cnt] = true;
        int nxt = getNew(cnt);
        if (nxt==TARGET) return eqt[cnt]=true;
        else if (nxt>TARGET) return eqt[cnt]=false;
        return eqt[cnt]=willReach(nxt,TARGET);
    }

    public static int getNew(int cnt) {
        int copy = cnt;
        while (copy>0) {
            cnt+=copy%10;
            copy/=10;
        }
        return cnt;
    }
}