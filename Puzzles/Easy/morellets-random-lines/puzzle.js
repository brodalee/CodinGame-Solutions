Array.prototype.unique = function() { return Array.from(new Set(this))}

const mapToNumbers = () => readline().split(' ').map(Number)
const [xA, yA, xB, yB] = mapToNumbers();
const getY = (x, [a, b, c]) => -((a * x + c) / b)
const getYs = (a) => [getY(xA, a), getY(xB, a)]
const reductionByMin = (a) => a.every(n => n % Math.min(...a) === 0) ? a.map(e => e / Math.min(...a)) : a
const sameSign = (a) => a[0] < 0 ? a.map(n => -1 * n) : a

const eqs = [...new Array(+readline())]
    .map(mapToNumbers)
    .map(reductionByMin)
    .map(sameSign)
    .map(e => e.toString())
    .unique()
    .map(e => e.split(',').map(Number))

let cntA = 0,cntB = 0

eqs.map(getYs).forEach(([_yA, _yB]) => {
    if (yA === _yA || yB === _yB) {
        console.log('ON A LINE')
        FonctionToStopArbitraryJavascriptExecution()
    }
    if (yA > _yA) cntA++
    if (yB > _yB) cntB++
})

console.log(cntA % 2 === cntB % 2 ? 'YES' : 'NO');
