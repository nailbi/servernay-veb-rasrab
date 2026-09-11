import subprocess
import pytest

# Для Windows
INTERPRETER = 'python'
# Для MAC
# INTERPRETER = 'python3'


def run_script(filename, input_data=None):
    proc = subprocess.run(
        [INTERPRETER, filename],
        input='\n'.join(input_data if input_data else []),
        capture_output=True,
        text=True,
        check=False
    )
    return proc.stdout.strip()


# ---------------------------------------------------------------------------
# 1) hello_world.py
# ---------------------------------------------------------------------------
def test_hello_world():
    assert run_script('hello_world.py') == 'Hello, world!'


# ---------------------------------------------------------------------------
# 2) python_if_else.py
# ---------------------------------------------------------------------------
python_if_else_data = [
    ('1', 'Weird'),
    ('4', 'Not Weird'),
    ('3', 'Weird'),
    ('6', 'Weird'),
    ('22', 'Not Weird'),
    ('2', 'Not Weird'),
    ('20', 'Weird'),
    ('99', 'Weird'),
    ('100', 'Not Weird'),
]


@pytest.mark.parametrize('input_data, expected', python_if_else_data)
def test_python_if_else(input_data, expected):
    assert run_script('python_if_else.py', [input_data]) == expected


# ---------------------------------------------------------------------------
# 3) arithmetic_operators.py
# ---------------------------------------------------------------------------
arithmetic_operators_data = [
    (['1', '2'], ['3', '-1', '2']),
    (['10', '5'], ['15', '5', '50']),
    (['3', '5'], ['8', '-2', '15']),
    (['100', '200'], ['300', '-100', '20000']),
    (['7', '7'], ['14', '0', '49']),
]


@pytest.mark.parametrize('input_data, expected', arithmetic_operators_data)
def test_arithmetic_operators(input_data, expected):
    assert run_script('arithmetic_operators.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 4) division.py
# ---------------------------------------------------------------------------
division_data = [
    (['3', '5'], ['0', '0.6']),
    (['10', '2'], ['5', '5.0']),
    (['7', '2'], ['3', '3.5']),
    (['5', '0'], ['Division by zero']),
]


@pytest.mark.parametrize('input_data, expected', division_data)
def test_division(input_data, expected):
    assert run_script('division.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 5) loops.py
# ---------------------------------------------------------------------------
loops_data = [
    (['1'], ['0']),
    (['3'], ['0', '1', '4']),
    (['5'], ['0', '1', '4', '9', '16']),
]


@pytest.mark.parametrize('input_data, expected', loops_data)
def test_loops(input_data, expected):
    assert run_script('loops.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 6) print_function.py
# ---------------------------------------------------------------------------
print_function_data = [
    (['1'], '1'),
    (['3'], '123'),
    (['5'], '12345'),
    (['9'], '123456789'),
]


@pytest.mark.parametrize('input_data, expected', print_function_data)
def test_print_function(input_data, expected):
    assert run_script('print_function.py', input_data) == expected


# ---------------------------------------------------------------------------
# 7) second_score.py
# ---------------------------------------------------------------------------
second_score_data = [
    (['5', '2 3 6 6 5'], '5'),
    (['3', '1 2 3'], '2'),
    (['4', '10 20 20 30'], '20'),
    (['2', '5 10'], '5'),
]


@pytest.mark.parametrize('input_data, expected', second_score_data)
def test_second_score(input_data, expected):
    assert run_script('second_score.py', input_data) == expected


# ---------------------------------------------------------------------------
# 8) nested_list.py
# ---------------------------------------------------------------------------
nested_list_data = [
    (['5', 'Гарри', '37.21', 'Берри', '37.21', 'Тина', '37.2',
      'Акрити', '41', 'Харш', '39'], ['Берри', 'Гарри']),
    (['3', 'chi', '20', 'beta', '50', 'alpha', '50'], ['alpha', 'beta']),
    (['2', 'x', '1', 'y', '2'], ['y']),
]


@pytest.mark.parametrize('input_data, expected', nested_list_data)
def test_nested_list(input_data, expected):
    assert run_script('nested_list.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 9) lists.py
# ---------------------------------------------------------------------------
lists_data = [
    (['4', 'append 1', 'append 2', 'insert 1 3', 'print'], ['[1, 3, 2]']),
    (['3', 'append 1', 'append 2', 'print'], ['[1, 2]']),
    (['12', 'insert 0 5', 'insert 1 10', 'insert 0 6', 'print', 'remove 6',
      'append 9', 'append 1', 'sort', 'print', 'pop', 'reverse', 'print'],
     ['[6, 5, 10]', '[1, 5, 9, 10]', '[9, 5, 1]']),
]


@pytest.mark.parametrize('input_data, expected', lists_data)
def test_lists(input_data, expected):
    assert run_script('lists.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 10) swap_case.py
# ---------------------------------------------------------------------------
swap_case_data = [
    ('Www.MosPolytech.ru', 'wWW.mOSpOLYTECH.RU'),
    ('Pythonist 2', 'pYTHONIST 2'),
    ('HELLO', 'hello'),
    ('abc XYZ', 'ABC xyz'),
]


@pytest.mark.parametrize('input_data, expected', swap_case_data)
def test_swap_case(input_data, expected):
    assert run_script('swap_case.py', [input_data]) == expected


# ---------------------------------------------------------------------------
# 11) split_and_join.py
# ---------------------------------------------------------------------------
split_and_join_data = [
    ('this is a string', 'this-is-a-string'),
    ('a b c', 'a-b-c'),
    ('hello', 'hello'),
]


@pytest.mark.parametrize('input_data, expected', split_and_join_data)
def test_split_and_join(input_data, expected):
    assert run_script('split_and_join.py', [input_data]) == expected


# ---------------------------------------------------------------------------
# 12) max_word.py (читает example.txt)
# ---------------------------------------------------------------------------
def test_max_word():
    assert run_script('max_word.py') == 'сосредоточенности'


# ---------------------------------------------------------------------------
# 13) price_sum.py (читает products.csv)
# ---------------------------------------------------------------------------
def test_price_sum():
    assert run_script('price_sum.py') == '6842.84 5891.06 6810.9'


# ---------------------------------------------------------------------------
# 14) anagram.py
# ---------------------------------------------------------------------------
anagram_data = [
    (['listen', 'silent'], 'YES'),
    (['hello', 'world'], 'NO'),
    (['abc', 'cab'], 'YES'),
    (['a', 'aa'], 'NO'),
    (['Dog', 'God'], 'NO'),
]


@pytest.mark.parametrize('input_data, expected', anagram_data)
def test_anagram(input_data, expected):
    assert run_script('anagram.py', input_data) == expected


# ---------------------------------------------------------------------------
# 15) metro.py
# ---------------------------------------------------------------------------
metro_data = [
    (['3', '1 5', '2 6', '4 8', '5'], '3'),
    (['2', '1 10', '5 20', '15'], '1'),
    (['2', '1 3', '5 8', '4'], '0'),
]


@pytest.mark.parametrize('input_data, expected', metro_data)
def test_metro(input_data, expected):
    assert run_script('metro.py', input_data) == expected


# ---------------------------------------------------------------------------
# 16) minion_game.py
# ---------------------------------------------------------------------------
minion_game_data = [
    (['BANANA'], 'Стюарт 12'),
    (['A'], 'Кевин 1'),
    (['B'], 'Стюарт 1'),
    (['AB'], 'Кевин 2'),
]


@pytest.mark.parametrize('input_data, expected', minion_game_data)
def test_minion_game(input_data, expected):
    assert run_script('minion_game.py', input_data) == expected


# ---------------------------------------------------------------------------
# 17) is_leap.py
# ---------------------------------------------------------------------------
is_leap_data = [
    ('2000', 'True'),
    ('1900', 'False'),
    ('2016', 'True'),
    ('2017', 'False'),
    ('2100', 'False'),
    ('2400', 'True'),
]


@pytest.mark.parametrize('input_data, expected', is_leap_data)
def test_is_leap(input_data, expected):
    assert run_script('is_leap.py', [input_data]) == expected


# ---------------------------------------------------------------------------
# 18) happiness.py
# ---------------------------------------------------------------------------
happiness_data = [
    (['3 2', '1 5 3', '3 1', '5 7'], '1'),
    (['2 2', '1 2', '1 2', '3 4'], '2'),
    (['3 1', '1 1 1', '2', '1'], '-3'),
]


@pytest.mark.parametrize('input_data, expected', happiness_data)
def test_happiness(input_data, expected):
    assert run_script('happiness.py', input_data) == expected


# ---------------------------------------------------------------------------
# 19) pirate_ship.py
# ---------------------------------------------------------------------------
pirate_ship_data = [
    (['50 3', 'gold 10 60', 'silver 20 100', 'bronze 30 120'],
     ['silver 20 100', 'bronze 20 80', 'gold 10 60']),
    (['60 3', 'gold 10 60', 'silver 20 100', 'bronze 30 120'],
     ['bronze 30 120', 'silver 20 100', 'gold 10 60']),
]


@pytest.mark.parametrize('input_data, expected', pirate_ship_data)
def test_pirate_ship(input_data, expected):
    assert run_script('pirate_ship.py', input_data).split('\n') == expected


# ---------------------------------------------------------------------------
# 20) matrix_mult.py
# ---------------------------------------------------------------------------
matrix_mult_data = [
    (['2', '1 2', '3 4', '5 6', '7 8'], ['19 22', '43 50']),
    (['2', '1 0', '0 1', '5 6', '7 8'], ['5 6', '7 8']),
    (['3', '1 0 0', '0 1 0', '0 0 1', '1 2 3', '4 5 6', '7 8 9'],
     ['1 2 3', '4 5 6', '7 8 9']),
]


@pytest.mark.parametrize('input_data, expected', matrix_mult_data)
def test_matrix_mult(input_data, expected):
    assert run_script('matrix_mult.py', input_data).split('\n') == expected
