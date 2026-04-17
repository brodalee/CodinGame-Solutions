<?php
fscanf(STDIN, "%d", $lengthofline);
fscanf(STDIN, "%d", $N);

$entries = [];
for ($i = 0; $i < $N; $i++) {
    $entry = stream_get_line(STDIN, 200 + 1, "\n");

    $level = 0;
    while ($level < strlen($entry) && $entry[$level] === '>') {
        $level++;
    }

    $rest   = substr($entry, $level);
    $parts  = explode(' ', $rest, 2);
    $title  = $parts[0];
    $page   = (int)$parts[1];

    $entries[] = ['level' => $level, 'title' => $title, 'page' => $page];
}

$counters = array_fill(0, 10, 0);

foreach ($entries as $entry) {
    $level   = $entry['level'];
    $title   = $entry['title'];
    $pageStr = (string)$entry['page'];

    $counters[$level]++;
    for ($i = $level + 1; $i < 10; $i++) {
        $counters[$i] = 0;
    }

    $num    = (string)$counters[$level];
    $indent = str_repeat(' ', $level * 4);

    $dotsCount = $lengthofline
        - strlen($indent)
        - strlen($num)
        - 1
        - strlen($title)
        - strlen($pageStr);

    $dots = str_repeat('.', $dotsCount);

    echo $indent . $num . ' ' . $title . $dots . $pageStr . "\n";
}