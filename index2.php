<?php
//Credits: some of the SQL/PHP code in this file is adapted from lab6, especially preparing SQL, and reporting errors

// open connection to database,
$db = new PDO('sqlite:data.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function exec_sql_query($db, $sql, $params) {
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return NULL;
}

// An array to deliver messages to the user.
$errorMessages = array();

// Search Form

const SEARCH_FIELDS = [
  "name" => "By name of video game",
  "release" => "By year released",
  "genre" => "By genre",
  "score" => "By score"
];

if (isset($_GET['search']) and isset($_GET['category'])) {
  $do_search = TRUE;

  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    array_push($errorMessages, "Invalid category for search.");
    $do_search = FALSE;
  }

  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  // No search provided, so set the product to query to NULL
  $do_search = FALSE;
  $category = NULL;
  $search = NULL;
}

// Insert Form

// Get the list of games from the database.
$games = exec_sql_query($db, "SELECT DISTINCT name FROM games", NULL)->fetchAll(PDO::FETCH_COLUMN);

if (isset($_POST["submit_insert"])) {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $release = filter_input(INPUT_POST, 'release', FILTER_VALIDATE_INT);
  $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);
  $score = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_INT);


  if ( $score>100 or $score<0 ) {
    $invalid_review = TRUE;
  }

  if ($invalid_review) {
    array_push($errorMessages, "Failed to add game. Invalid game or rating.");
  } else {
    $sql = "INSERT INTO games (release, score, name, genre ) VALUES (:release, :score, :name, :genre)";
    $params = array(
      ':release' => $release,
      ':score' => $score,
      ':name' => $name,
      ':genre' => $genre
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      array_push($errorMessages, "Your game review has been added. Thank you!");
    } else {
      array_push($errorMessages, "Failed to add game.");
    }
  }
}

function print_record($record) {
  ?>
  <tr>
    <td><?php echo htmlspecialchars($record["release"]);?></td>
    <td><?php echo htmlspecialchars($record["score"]);?></td>
    <td><?php echo htmlspecialchars($record["name"]);?></td>
    <td><?php echo htmlspecialchars($record["genre"]);?></td>
  </tr>
  <?php
}
?>
<!DOCTYPE html>
<html>
<?php include "includes/head.php"; ?>
<body>
  <div id="Main-Page">
    <a href="index.php">
    <h1 id="openingH1">Video Game Reviews
    </h1>
</a>
    <p>Explore the video game classics of the last decade!</p>

    <?php
    // Write out any messages to the user, adapted from lab06
    foreach ($errorMessages as $error) {
      echo "<p><strong>" . htmlspecialchars($error) . "</strong></p>\n";
    }
    ?>

    <form id="searchForm" action="index.php" method="get">
      <select name="category">
        <option value="" selected disabled>Search By</option>
        <?php
        foreach(SEARCH_FIELDS as $field_name => $label){
          ?>
          <option value="<?php echo $field_name;?>"><?php echo $label;?></option>
          <?php
        }
        ?>
      </select>
      <input type="text" name="search"/>
      <button type="submit">Search</button>
    </form>

    <?php
    if ($do_search) {
      // Game to query
      ?>
      <h2>Search Results</h2>
      <?php
      $sql = "SELECT * FROM games WHERE " . $search_field . " LIKE '%' || :search || '%'";
      $params = array(
        ':search' => $search
      );
    } else {
      // returns all games
      ?>
      <h2>All Reviews</h2>
      <?php

      $sql = "SELECT * FROM games";
      $params = array();
    }

    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if (isset($records) and !empty($records)) {
      ?>
      <table id="mainTable">
        <tr>
          <th>Release</th>
          <th>Score</th>
          <th>Name</th>
          <th>Genre</th>
        </tr>
        <?php

        foreach($records as $record) {
          print_record($record);
        }
        ?>
      </table>
      <?php
    } else {
      echo "<p>No games found :(</p>";
    }
    ?>

    <h3>Add a video game review</h3>

    <form id="reviewGame" action="index.php" method="post">
      <ul>
        <li>
          <label>Video Game Title:</label>
          <input type="text" name="name" required/>
        </li>

        <li>
          <label>Release Year:</label>
          <select name="release" required>
            <option value="" selected disabled>Release Year</option>
            <?php
            $years = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018);
            foreach($years as $year) {
              echo "<option value=\"" . $year . "\">" . $year. "</option>";
            }
            ?>
          </select>
        </li>

        <li>
          <label>Score:</label>
          <input type="number" placeholder="100" min="0" max="100" name="score" required/>
        </li>

        <li>
          <label>Genre:</label>
          <select name="genre" required>
            <option value="" selected disabled>Choose Genre</option>
            <?php
            $genres = array("Shooting","Multiplayer","RPG","Racing","Adventure","Simulation","Action","Sports");
            sort($genres);
            foreach($genres as $gen) {
              echo "<option value=\"" . $gen. "\">" . $gen. "</option>";
            }
            ?>
          </select>
      </ul>

      <button name="submit_insert" type="submit">Add Review</button>

    </form>
  </div>
<?php include "includes/footer.php"; ?>
</body>
</html>
