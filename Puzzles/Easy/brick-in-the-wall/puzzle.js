const X = +readline();
readline();

const weights = readline()
    .split(' ')
    .map(Number)
    .sort((a, b) => a - b);

const total = weights.reduceRight(
    ({ total, floor, bricksInRow }, currWeight) => {
        if (bricksInRow === X) {
            floor++;
            bricksInRow = 0;
        }
        bricksInRow++;
        total += (((floor - 1) * 6.5) / 100) * 10 * currWeight;
        return { total, floor, bricksInRow };
    },
    { total: 0.0, floor: 1, bricksInRow: 0 }
).total;

console.log(total.toFixed(3));
