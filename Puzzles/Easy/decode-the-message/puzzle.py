import sys

n = int(input())
a = input()

lena = len(a)
n+=1
ss = ""
while n>0:
    r = n%lena-1
    if r<0:
        r=lena-1
    cc = a[r]
    ss+=cc
    n = (n-r)//lena
print(ss)