const players = [...Array(+(readline()))].map(() => {
  return { name: readline(), stats: { score: 0, lastRoundScore: 0, throws: 0, missStreak: 0, rounds: 0}, shots: [] }
})

players.forEach(p => (p.shots = readline().split(' ').map(s => (s === 'X' ? 0 : eval(s)))))

players.forEach(p => {
  (p.stats = p.shots
    .reduce( (s, c) => {
        if (s.throws == 3) {
          s.rounds++
          s.lastRoundScore = s.score
          s.throws = 0
          s.missStreak = 0
        }
        s.throws++

        if (c == 0) {
          s.missStreak++
          s.score = s.missStreak === 1
            ? s.score - 20
            : s.missStreak == 2
              ? s.score - 30
              : 0
          s.score = s.score < 0 ? 0 : s.score
        } else {
          if (s.score + c > 101) {
            s.score = s.lastRoundScore
            s.throws = 3
          } else {
            s.missStreak = 0
            s.score += c
          }
        }
        return s
      }, { score: 0, lastRoundScore: 0, throws: 0, missStreak: 0, rounds: 0}
    ))
})

console.log(
  players
    .filter(p => p.stats.score === 101)
    .reduce( (bp, cp) => {
      if (
        (cp.stats.rounds == bp.stats.rounds &&
          cp.stats.throws < bp.stats.throws) ||
        cp.stats.rounds < bp.stats.rounds
      ) {
        return cp
      }
      return bp
    }).name
)
