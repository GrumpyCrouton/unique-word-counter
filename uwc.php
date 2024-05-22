<?php
    session_start();

    if(!array_key_exists('words', $_SESSION)) {
        $_SESSION['words'] = [];
    }

    if (!empty($_POST)) {
        $values = $_POST;

        if(array_key_exists('reset', $values) && $values['reset'] == 1) {
            $_SESSION['words'] = [];
        }

        $text_to_count = trim(strtolower($values['text_to_count']));

        $text_to_count = str_replace('-', ' ', $text_to_count);

        // Combine all patterns into one for a single preg_replace call
        $pattern = '/(?:\[[^\]]*\]|\{[^}]*\}|\([^)]*\)|[^a-zA-Z\s\n])+/';

        // Remove content inside brackets: [], {}, (), and special characters
        $text_to_count = preg_replace($pattern, '', $text_to_count);

        $words = array_filter(preg_split('/\s+/', $text_to_count));

        foreach($words as $word) {
            if(strlen($word) <= 2) continue;
            if(!array_key_exists($word, $_SESSION['words'])) {
                $_SESSION['words'][$word] = 0;
            }
            $_SESSION['words'][$word]++;
        }

        header('Location: '.$_SERVER['REQUEST_URI']);
        die();
    }
?>

<h1>Unique Word Counter</h1>

<p>
    When you press submit, it will take the input and do the following actions.
    <ul>
        <li>Convert the text to lowercase</li>
        <li>Replace the <code>-</code> symbol to a space</li>
        <li>Remove all text that is within brackets (e.g <code>[]</code>, <code>{}</code>, <code>()</code>)</li>
        <li>Remove all characters that are not spaced or letters</li>
        <li>Separate all of the text into individual words</li>
        <li>Track the count of all words that are longer than 2 characters</li>
    </ul>
    This page can be submitted as many times as you want with different text to count unique words over multiple sets of text.
</p>

<div>
    <form method="POST">
        <textarea name="text_to_count" style="width: 500px; height: 500px;"></textarea>
        <div style="margin-top: 5px;">
            <button type="submit">Submit</button>
            <button type="submit" name="reset" value="1">Reset</button>
        </div>
    </form>
</div>

<?php
    $words = $_SESSION['words'];
    arsort($words, SORT_NUMERIC);

    echo "Unique Word Count: " . count($words);
    echo "<pre>" . print_r($words,true) . "</pre>";
?>
