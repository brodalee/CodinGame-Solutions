let inputs = readline().split(' ');
const W = parseInt(inputs[0]);
const H = parseInt(inputs[1]);
const T1 = parseInt(inputs[2]);
const T2 = parseInt(inputs[3]);
const T3 = parseInt(inputs[4]);

const firstPictureRows = [];
const secondPictureRows = [];
const asteroids = [];
let output = Array.from({ length: H }, () => Array(W).fill('.'));

const dt21 = T2 - T1;
const dt32 = T3 - T2;

for (let i = 0; i < H; i++) {
    let [f_row, s_row] = readline().split(' ');
    firstPictureRows.push(f_row);
    secondPictureRows.push(s_row);
    for (const char of f_row) {
        if (char !== '.') {
            asteroids.push(char);
        }
    }
}
asteroids.sort().reverse();

for (const asteroid of asteroids) {
    let xy_first = [null, null], xy_second = [null, null];
    for (let i = 0; i < H; i++) {
        for (let j = 0; j < W; j++) {
            if (firstPictureRows[i][j] === asteroid) {
                xy_first = [i, j];
            }
            if (secondPictureRows[i][j] === asteroid) {
                xy_second = [i, j];
            }
            if (xy_first[0] !== null && xy_second[0] !== null) {
                const tx = (xy_second[0] - xy_first[0]) / dt21;
                const ty = (xy_second[1] - xy_first[1]) / dt21;

                const cx = Math.floor(xy_second[0] + tx * dt32);
                const cy = Math.floor(xy_second[1] + ty * dt32);

                if (0 <= cx && cx < H && 0 <= cy && cy < W) {
                    output[cx][cy] = asteroid;
                }
            }
        }
    }
}

for (const row of output) {
    console.log(row.join(''));
}