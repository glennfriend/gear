<?php

    $mainMenu = MenuManager::getMain();
    if ( !$mainMenu ) {
        return;
    }

    $subKey = MenuManager::getSubKey();
    $user = UserManager::getUser();

?>

                    <div class="panel panel-default">
                        <div class="panel-heading"><?php echo $mainMenu['main']['label'] ?></div>
                        <div class="list-group">
                        <?php
                            $output = '';
                            foreach ( $mainMenu['sub'] as $sub ) {

                                if ( !$user->hasPermission( array($sub['role']) ) ) {
                                    continue;
                                }

                                if ( $sub['key'] === $subKey ) {
                                    $output .= '<a href="'. $sub['link'] .'" class="list-group-item active">'. $sub['label'] .'</a>'; 
                                }
                                else {
                                    $output .= '<a href="'. $sub['link'] .'" class="list-group-item">'.        $sub['label'] .'</a>'; 
                                }
                            }
                            echo $output;
                        ?>
                        </div>
                    </div>

