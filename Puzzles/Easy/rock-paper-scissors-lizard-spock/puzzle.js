const N = parseInt(readline())
const phases = []

for (let i = 0; i < N; i++) {
    const inputs = readline().split(' ')
    const NUMPLAYER = parseInt(inputs[0])
    const SIGNPLAYER = inputs[1]
    phases.push({sign: SIGNPLAYER, num: NUMPLAYER, op: []})
}

const determineWinner = (player1, player2) => {
    const rules = {
        // Rock crushes Lizard
        // Rock crushes Scissors
        'R': ['C', 'L'],

        // Paper covers Rock
        // Paper disproves Spock
        'P': ['R', 'S'],

        // Scissors decapitates Lizard
        // Scissors cuts Paper
        'C': ['P', 'L'],

        // Lizard poisons Spock
        // Lizard eats Paper
        'L': ['P', 'S'],

        // Spock vaporizes Rock
        // Spock smashes Scissors
        'S': ['C', 'R']
    }

    if (player1.sign === player2.sign) {
        if (player1.num < player2.num) {
            return player1
        }
        return player2
    }

    if (rules[player1.sign].includes(player2.sign)) {
        return player1
    }

    return player2
}

while (phases.length > 1) {
    const nextPhases = [];
    for (let i = 0; i < phases.length; i += 2) {
        const player1 = phases[i];
        const player2 = phases[i + 1];
        const winner = determineWinner(player1, player2);
        nextPhases.push({ ...winner, op: [...winner.op ?? [], winner.num === player1.num ? player2.num : player1.num] });
    }
    phases.splice(0, phases.length, ...nextPhases);
}
console.log(phases[0].num)
console.log(phases[0].op.join` `)
