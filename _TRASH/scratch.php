<?php

do {

    $path[] = ['x' => 1, 'y' => 1];
    $x = $path[0]['x'];
    $y = $path[0]['y'];
    $failed_attempt = 0;

    echo "--------------- path is too short. now generating a new map ---------------\n";

    do {

        switch(rand(1,4)) {
            case 1 :
                $x++;
                if(neighbour_verification($path, $x, $y) > 1 or $x > 19) {
                    $failed_attempt++;
                    echo "check failed... tried $failed_attempt time...\n";
                    $x--;
                break;
                } else {
                    $failed_attempt = 0;
                    $path[] = ['x' => $x, 'y' => $y];
                    echo "check succeed !\n";
                break;
                }

            case 2 :
                $x--;
                if(neighbour_verification($path, $x, $y) > 1 or $x < 1) {
                    $failed_attempt++;
                    echo "check failed... tried $failed_attempt time...\n";
                    $x++;
                break;
                } else {
                    $failed_attempt = 0;
                    $path[] = ['x' => $x, 'y' => $y];
                    echo "check succeed !\n";
                break;
                }

            case 3 :
                $y++;
                if(neighbour_verification($path, $x, $y) > 1 or $y > 19) {
                    $failed_attempt++;
                    echo "check failed... tried $failed_attempt time...\n";
                    $y--;
                break;
                } else {
                    $failed_attempt = 0;
                    $path[] = ['x' => $x, 'y' => $y];
                    echo "check succeed !\n";
                break;
                }

            case 4 :
                $y--;
                if(neighbour_verification($path, $x, $y) > 1 or $y < 1) {
                    $failed_attempt++;
                    echo "check failed... tried $failed_attempt time...\n";
                    $y++;
                break;
                } else {
                    $failed_attempt = 0;
                    $path[] = ['x' => $x, 'y' => $y];
                    echo "check succeed !\n";
                break;
                }
        }

        if($failed_attempt > 7) {
            echo "10 consecutive failed attempt reached. Path probably stuck in a dead end. Generating a new path......\n";
            $failed_attempt = 0;
            $path = [];
            $path[] = ['x' => 1, 'y' => 1];
            $x = $path[0]['x'];
            $y = $path[0]['y'];
        }

    } while ( $x != 19 or $y != 19);

} while (count($path) < 60);

function check_success() {
    $failed_attempt = 0;
    $path[] = ['x' => $x, 'y' => $y];
    echo "check succeed !\n";
}

// check how many path box will be beside next random path box
function neighbour_verification($array, $x, $y) {

    $test_match = 0;

    foreach($array as $value) {
        if ($value['x'] == $x + 1 and $value['y'] == $y) {$test_match++;}
        if ($value['x'] == $x - 1 and $value['y'] == $y) {$test_match++;}
        if ($value['x'] == $x and $value['y'] == $y + 1) {$test_match++;}
        if ($value['x'] == $x and $value['y'] == $y - 1) {$test_match++;}
    }

    echo "checkin path box...\n";
    return $test_match;
}

print_r($path);