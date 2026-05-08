<?php

class ElearningBridgeController
{
    public function frontCatalog(): void
    {
        header('Location: e-learning/index.php?r=front/formations', true, 302);
        exit;
    }
}
