import sys
import math
from collections import deque, namedtuple
from typing import List, Optional

Position = namedtuple('Position', ['x', 'y'])

w, h = [int(i) for i in input().split()]
maze = []


def calculate_bfs_distance(start: Position, destination: Position) -> Optional[str]:
    queue: deque = deque([(start, 0)])
    visited_cell: set = {start}
    while queue:
        cell, distance = queue.popleft()
        if cell == destination:
            return str(distance) if distance < 10 else chr(distance + 55)

        for dx, dy in {(0, 1), (-1, 0), (0, -1), (1, 0)}:
            nX = cell.x + dx
            nY = cell.y + dy

            if nX == w:
                nX = 0
            elif nX == -1:
                nX = w - 1
            elif nY == h:
                nY = 0
            elif nY == -1:
                nY = h - 1

            nCell: Position = Position(nX, nY)
            if maze[nY][nX] == '#' or nCell in visited_cell:
                continue

            queue.append((nCell, distance + 1))
            visited_cell.add(nCell)


for i in range(h):
    row = input()
    maze.append(list(map(str, list(row))))
    if 'S' in row:
        start: Position = Position(x=row.index('S'), y=i)


for i in range(h):
    distances: List[str] = maze[i]
    if start.y == i:
        distances[start.x] = '0'
    
    for x in range(w):
        if maze[i][x] != '#':
            distance: Optional[str] = calculate_bfs_distance(start=start, destination=Position(x, i))
            if distance:
                distances[x] = distance

    result: str = ''.join(distances)
    print(result)
