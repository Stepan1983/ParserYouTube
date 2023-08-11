<?php

function getYouTubeVideos($apiKey, $searchQuery, $numVideos) {
    $url = "https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults={$numVideos}&q=" . urlencode($searchQuery) . "&key=" . $apiKey;

    $response = file_get_contents($url);
    $responseData = json_decode($response, true);

    $videoIds = [];

    if (!empty($responseData['items'])) {
        foreach ($responseData['items'] as $item) {
            $videoId = $item['id']['videoId'];
            $videoIds[] = $videoId;
        }
    }

    return $videoIds;
}

$apiKey = file_get_contents("keyApiYoutube.txt");
$searchQuery = '';
$numVideos = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchQuery = $_POST['searchQuery'];
    $numVideos = $_POST['numVideos'];

    $videoIds = getYouTubeVideos($apiKey, $searchQuery, $numVideos);

    if (!empty($videoIds)) {
        $videoIdsString = implode("\n", $videoIds);
        file_put_contents('ids.txt', $videoIdsString);
        echo "Идентификаторы видео сохранены в файле ids.txt";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Поиск видео на YouTube</title>
</head>
<body>
    <h1>Поиск видео на YouTube</h1>

    <form method="POST" action="index.php">
        <label for="searchQuery">Запрос:</label>
        <input type="text" name="searchQuery" id="searchQuery" value="<?php echo $searchQuery; ?>" required>
        <br>
        <label for="numVideos">Количество видео:</label>
        <input type="number" name="numVideos" id="numVideos" min="1" max="10" value="<?php echo $numVideos; ?>" required>
        <br>
        <input type="submit" value="Отправить">
    </form>

</body>
</html>
