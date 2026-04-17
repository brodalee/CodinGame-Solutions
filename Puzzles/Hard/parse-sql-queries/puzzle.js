/**
 * Auto-generated code below aims at helping you parse
 * the standard input according to the problem statement.
 **/

const sqlQuery = readline();
const ROWS = parseInt(readline());
const tableHeader = readline();
const tableRows = []
for (let i = 0; i < ROWS; i++) {
    tableRows.push(readline())
}

let objects = []
let str = '' //tableHeader + "\n"

const properties = tableHeader.split(' ')
tableRows.forEach(tr => {
    const ppts = tr.split(' ')
    const obj = {}
    properties.forEach((p, position) => {
        obj[p] = ppts[position]
    })

    objects.push(obj)
})

// TODO : parse sql query

const columns = sqlQuery
    .replace(/SELECT[ ]/s, '')
    .replace(/[ ]FROM(.*)/s, '')

const hasWhereCondition = sqlQuery.includes('WHERE')
const hasOrderCondition = sqlQuery.includes('ORDER')
const lines = []

if (columns === '*') {
    lines.push(tableHeader)
    const rows = []

    objects.forEach((o, i) => {
        if (hasWhereCondition) {
            const condition = sqlQuery
                .replace(/SELECT(.*)FROM[ ][a-zA-Z]+/, '')
                .replace(/ORDER[ ]BY(.*)/, '')
                .replace(/WHERE[ ]/, '')
                .trim()
            const columnCondition = condition.replace(/[ ]=(.*)/, '')
            const valueCondition = condition.replace(/(.*)[ ]=[ ]/, '')
            if (o[columnCondition] == valueCondition) {
                rows.push(o)
            }
        } else {
            rows.push(o)
        }
    })

    if (hasOrderCondition) {
        const condition = sqlQuery
            .replace(/(.*)ORDER/, '')
            .replace('ORDER', '')
            .replace('BY', '')
            .trim()

        const columnName = condition.split(' ')[0].trim()
        const orderType = condition.split(' ')[1].trim()

        if (orderType == 'DESC') {
            rows.sort((a, b) => parseFloat(b[columnName]) - parseFloat(a[columnName]))
        } else {
            rows.sort((a, b) => parseFloat(a[columnName]) - parseFloat(b[columnName]))
        }
    }

    console.error(rows)

    rows.forEach(r => {
        let line = []
        Object.getOwnPropertyNames(r).forEach(pname => {
            const value = r[pname]
            line.push(value)
        })

        lines.push(line.join(' '))
    })
} else {
    const filteredColumns = columns.split(', ')
    lines.push(filteredColumns.join(' '))
    const rows = []

    objects.forEach((o, i) => {
        if (hasWhereCondition) {
            const condition = sqlQuery
                .replace(/SELECT(.*)FROM[ ][a-zA-Z]+/, '')
                .replace(/ORDER[ ]BY(.*)/, '')
                .replace(/WHERE[ ]/, '')
                .trim()
            const columnCondition = condition.replace(/[ ]=(.*)/, '')
            const valueCondition = condition.replace(/(.*)[ ]=[ ]/, '')
            if (o[columnCondition] == valueCondition) {
                rows.push(o)
            }
        } else {
            rows.push(o)
        }
    })

    if (hasOrderCondition) {
        const condition = sqlQuery
            .replace(/(.*)ORDER/, '')
            .replace('ORDER', '')
            .replace('BY', '')
            .trim()

        const columnName = condition.split(' ')[0].trim()
        const orderType = condition.split(' ')[1].trim()

        if (orderType == 'DESC') {
            rows.sort((a, b) => parseFloat(b[columnName]) - parseFloat(a[columnName]))
        } else {
            rows.sort((a, b) => parseFloat(a[columnName]) - parseFloat(b[columnName]))
        }
    }

    rows.forEach(r => {
        let line = []
        filteredColumns.forEach(c => {
            line.push(r[c])
        })

        lines.push(line.join(' '))
    })
}
lines[lines.length - 1].replace("\n", '')
console.log(lines.join("\n"))