<?php

class Notification{

    public function notificationRouge($message){
        
        echo <<<HTML
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Erreur :</strong> {$message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            HTML;
    }

    public function notificationVert($message){
        
        echo <<<HTML
            <div class="alert alert-succes alert-dismissible fade show" role="alert">
                <strong>Erreur :</strong> {$message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            HTML;
    }
}
