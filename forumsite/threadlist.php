<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>iDiscuss - Coding Forum</title>
    <style>
    #ques {
        min-height: 208px;
    }
    </style>
</head>

<body>

    <?php include 'partials/_dbconnect.php' ?>
    <?php include 'partials/_header.php' ?>

    <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `categories` WHERE category_id=$id";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
           $catname = $row['category_name']; 
           $catdesc = $row['category_description']; 
        }
        ?>
    <?php
        $showAlert = false;
            $method = $_SERVER['REQUEST_METHOD'];
            if($method=='POST'){
                // insert into db
                $th_title = $_POST['title'];
                $th_desc = $_POST['desc'];
                $sno = $_POST['sno'];
                $sql = "INSERT INTO `threads` ( `thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) VALUES ('$th_title', '$th_desc', '$id', '$sno', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                
                $showAlert = true;
                if($showAlert){
                   echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong> Success!</strong> Your thread has be been added please wait for someone to respond
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
            }
        ?>

    <div class="container my-3">
        <div class="jumbotron bg-light m-2 ">
            <h1 class="display-4">Welcome to <?php echo $catname;?> Forum</h1>
            <p class="lead"><?php echo $catdesc;?></p>
            <hr class="my-4">
            <p>No Spam / Advertising / Self-promote in the forums. Do not post copyright-infringing material. Do not
                post “offensive” posts, links or images. Do not cross post questions. Do not PM users asking for help.
                Remain respectful of other members at all times</p>
            <p class="lead">
                <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
            </p>
        </div>

        <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        echo '<div class="container">
            <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                <div class="form-group">
                    <label for="exampleInputEmail1">Problem Title</label>
                    <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp"
                        placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">Keep your title short snd crisp as
                        possible</small>
                </div>
                <div class="form-group my-2">
                    <label for="exampleFormControlTextarea1">Elaborate your Problem</label>
                    <textarea type="text" class="form-control" id="desc" name="desc" rows="3"></textarea>
                    <input type="hidden" name="sno" value="'. $_SESSION['sno'] .'">
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>';
    }
    else{
     echo  '<div class="container">
     <h1 class="my-3">Start a Discussion</h1>
     <p class="lead">You are not logged in. Please login to ask a question</p>
        </div>';
    }
        ?>

        <div class="container" id="ques">
            <h1 class="my-3">Browse Question</h1>
            <?php
        $id = $_GET['catid'];
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id =$id";
        $result = mysqli_query($conn, $sql);
        $noResult = true;
        while($row = mysqli_fetch_assoc($result)){
            $noResult = false;
           $id = $row['thread_id']; 
           $title = $row['thread_title']; 
           $desc = $row['thread_desc'];
           $thread_time = $row['timestamp']; 
           $thread_user_id = $row['thread_user_id']; 
           $sql2 = "SELECT user_email FROM `users` where sno='$thread_user_id'";
           $result2 = mysqli_query($conn, $sql2);
           $row2 = mysqli_fetch_assoc($result2);
            
        echo '<div class="media">
            <img class="my-2" src="img/userdefault.png" width="40px" alt="">
            <div class="media-body">'.
                '<h5 class="mt-2"><a class="text-dark" href="thread.php?threadid=' .$id. '">'. $title .'</a></h5>
                '. $desc .'<p class="font-weight-bold my-0"><b>Asked by '. $row2['user_email'] .' at '. $thread_time .'</b></p>'.
            '</div>
        </div>';
         }

         if($noResult){
             echo '<div class="jumbotron jumbotron-fluid bg-light">
             <div class="container">
               <h1 class="display-4">No Threads Found</h1>
               <p class="lead">Be the first to ask a question</p>
             </div>
           </div>';
         }
        ?>
        </div>
    </div>



    <?php include 'partials/_footer.php' ?>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>