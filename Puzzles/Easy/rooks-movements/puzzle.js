const ascii = (a) => a.charCodeAt(0);
const toIndex = (c) => (ascii(c) - ascii('a')) % 26;
const toChar = (c) => String.fromCharCode(c + ascii('a'));
const LEGAL_MOVE = '+'; const CAPTURED = 'x'; const EMPTY = '-';

const [rCol, rRow] = readline().split('');
const pos = {col: toIndex(rCol)+1, row: +rRow};

const pieces = [...Array(+readline())].map(() => {
    const [color, col, row] = readline().split(' ').join('').split('');
    return {color: color === '0'? 'W' : 'B', col: toIndex(col)+1, row: +row};
});

const board = [...Array(8)].map(() => Array(8).fill('-'));
pieces.forEach((c) => board[c.row-1][c.col-1] = c.color);
board[pos.row-1][pos.col-1] = 'R';

board.reverse();

for (let j = pos.col; j < 8; j++) {
    if (board[8-pos.row][j] !== EMPTY) {
        if (board[8-pos.row][j] === 'B') board[8-pos.row][j] = CAPTURED;
        break;
    }
    board[8-pos.row][j] = LEGAL_MOVE;
}

for (let j = pos.col-2; j >= 0; j--) {
    if (board[8-pos.row][j] !== EMPTY) {
        if (board[8-pos.row][j] === 'B') board[8-pos.row][j] = CAPTURED;
        break;
    }
    board[8-pos.row][j] = LEGAL_MOVE;
}

for (let j = (8 - pos.row+1); j < 8; j++) {
    if (board[j][pos.col-1] !== EMPTY) {
        if (board[j][pos.col-1] === 'B') board[j][pos.col-1] = CAPTURED;
        break;
    }
    board[j][pos.col-1] = LEGAL_MOVE;
}

for (let j = (8 - pos.row-1); j >= 0; j--) {
    if (board[j][pos.col-1] !== EMPTY) {
        if (board[j][pos.col-1] === 'B') board[j][pos.col-1] = CAPTURED;
        break;
    }
    board[j][pos.col-1] = LEGAL_MOVE;
}

board.reverse();
const after = [];
const outString = (str, col, row) =>
    `R${toChar(pos.col-1)}${pos.row}${
        str === LEGAL_MOVE ? '-' : str}${toChar(col)}${row+1}`;

for (let col = 0; col < 8; col++) {
    for (let row = 0; row < 8; row++) {
        const curr = board[row][col];
        if (curr === LEGAL_MOVE) console.log(outString(LEGAL_MOVE, col, row));
        else if (curr === CAPTURED) after.push(outString(CAPTURED, col, row));
    }
}
after.forEach((c) => console.log(c));
