let N: number = parseInt(readline());
let s = ''

do {
  let r = (N + 30000) % 3

  N-=[0,1,-1][r]

  N = N/3 |0

  s = "01T"[r] + s
} while (N != 0)

console.log(s);