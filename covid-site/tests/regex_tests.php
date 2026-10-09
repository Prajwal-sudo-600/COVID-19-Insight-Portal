<?php
require_once '../php/validate.php';

$testData = [
    'fullName' => [
        ['input' => 'John Doe', 'expected' => true],
        ['input' => 'Jane-Ann Smith', 'expected' => true],
        ['input' => 'J', 'expected' => false],
        ['input' => '123 Name', 'expected' => false],
        ['input' => 'John_Doe', 'expected' => false],
        ['input' => '', 'expected' => false],
        ['input' => '   ', 'expected' => false],
        ['input' => 'Averyveryveryveryveryveryveryveryveryverylongname indeed', 'expected' => false]
    ],
    'username' => [
        ['input' => 'user123', 'expected' => true],
        ['input' => 'a_b_c_d', 'expected' => true],
        ['input' => '1user', 'expected' => false],
        ['input' => 'us', 'expected' => false],
        ['input' => 'user@name', 'expected' => false],
        ['input' => 'thisusernameistoolong', 'expected' => false],
        ['input' => '', 'expected' => false],
        ['input' => 'user name', 'expected' => false]
    ],
    'email' => [
        ['input' => 'test@example.com', 'expected' => true],
        ['input' => 'user.name+tag@domain.co.uk', 'expected' => true],
        ['input' => 'invalid-email', 'expected' => false],
        ['input' => '@domain.com', 'expected' => false],
        ['input' => 'user@.com', 'expected' => false],
        ['input' => 'user@domain', 'expected' => false],
        ['input' => 'user@domain.c', 'expected' => false],
        ['input' => '', 'expected' => false]
    ],
    'password' => [
        ['input' => 'Pass123!', 'expected' => true],
        ['input' => 'Str0ng#Pw', 'expected' => true],
        ['input' => 'password', 'expected' => false],
        ['input' => 'PASSWORD123', 'expected' => false],
        ['input' => 'Pass123', 'expected' => false],
        ['input' => 'Pa1!', 'expected' => false],
        ['input' => ' ', 'expected' => false],
        ['input' => 'Pass 123!', 'expected' => true]
    ],
    'phone' => [
        ['input' => '9876543210', 'expected' => true],
        ['input' => '6789012345', 'expected' => true],
        ['input' => '5678901234', 'expected' => false],
        ['input' => '1234567890', 'expected' => false],
        ['input' => '987654321', 'expected' => false],
        ['input' => '98765432101', 'expected' => false],
        ['input' => '987654321a', 'expected' => false],
        ['input' => '', 'expected' => false]
    ],
    'age' => [
        ['input' => '25', 'expected' => true],
        ['input' => '120', 'expected' => true],
        ['input' => '1', 'expected' => true],
        ['input' => '0', 'expected' => false],
        ['input' => '121', 'expected' => false],
        ['input' => '-5', 'expected' => false],
        ['input' => 'abc', 'expected' => false],
        ['input' => '', 'expected' => false]
    ],
    'city' => [
        ['input' => 'New York', 'expected' => true],
        ['input' => 'London', 'expected' => true],
        ['input' => 'A', 'expected' => false],
        ['input' => 'City123', 'expected' => false],
        ['input' => 'This city name is definitely way too long for the field', 'expected' => false],
        ['input' => '', 'expected' => false],
        ['input' => '   ', 'expected' => false],
        ['input' => 'Paris-Ville', 'expected' => false]
    ],
    'comment' => [
        ['input' => 'This is a valid comment.', 'expected' => true],
        ['input' => 'Another comment with numbers 123!', 'expected' => true],
        ['input' => '<script>alert(1)</script>', 'expected' => false],
        ['input' => '<b>Bold text</b>', 'expected' => false],
        ['input' => '', 'expected' => false],
        ['input' => str_repeat('a', 501), 'expected' => false],
        ['input' => 'Just some text', 'expected' => true],
        ['input' => 'Text with < tag', 'expected' => true]
    ]
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Regex Tests (Server-side)</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .pass { background-color: #d4edda; }
        .fail { background-color: #f8d7da; }
    </style>
</head>
<body>
    <h1>Regex Tests (Server-side PHP)</h1>
    <?php foreach ($testData as $field => $tests): ?>
        <h2><?= htmlspecialchars($field) ?></h2>
        <table>
            <tr><th>Input</th><th>Expected</th><th>Actual</th><th>Result</th></tr>
            <?php foreach ($tests as $test): 
                $actual = validateField($field, $test['input']);
                $passed = ($actual === $test['expected']);
                $rowClass = $passed ? 'pass' : 'fail';
            ?>
            <tr class="<?= $rowClass ?>">
                <td><?= htmlspecialchars($test['input']) ?></td>
                <td><?= $test['expected'] ? 'true' : 'false' ?></td>
                <td><?= $actual ? 'true' : 'false' ?></td>
                <td><?= $passed ? 'PASS' : 'FAIL' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</body>
</html>
