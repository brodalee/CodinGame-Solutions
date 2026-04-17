let N = parseInt(readline());
let clauseCounter = 0;

let text = '';
while (N-- > 0) {
    text = text.concat(readline() + '\n');
}

let scheme = text.match(/\(.*?[^]*?\)/g);

scheme.forEach(clause => {
    const splittedChoices = clause
        .replace('(', '')
        .replace(')', '')
        .split(/[|]/g);
    const choice = splittedChoices[clauseCounter++ % splittedChoices.length];
    text = text.replace(clause, choice);
});

print(text);
