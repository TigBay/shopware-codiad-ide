<?php

class Shopware_Controllers_Backend_Codiad extends Shopware_Controllers_Backend_ExtJs {

    public function preDispatch()
    {
        $pluginPath = $this->container->getParameter('codiad_i_d_e.plugin_dir');
        $this->get('template')->addTemplateDir($pluginPath . '/Views/');
    }
    
    public function getUrlAction(){

        $shopwarePath = Shopware()->Container()->getParameter('kernel.root_dir');

        $pluginPath = Shopware()->Container()->getParameter('codiad_i_d_e.plugin_dir');
		
		$FullPluginPath = Shopware()->Container()->getParameter('codiad_i_d_e.plugin_fullpath');

        $pathToPluginFolder = $pluginPath."/codiad";

        $pluginURL = Shopware()->Container()->getParameter('codiad_i_d_e.plugin_url');

        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $oDbData = Shopware()->Container()->getParameter('shopware.db');

        if (!file_exists($pathToPluginFolder . "/config.php")) {
            $config = file_get_contents($pathToPluginFolder . "/config.example");

            $config = str_replace(
                ['###BASE_PATH###','###BASE_URL###'],
                ["$FullPluginPath","$pluginURL"],
                $config
            );

            file_put_contents($pathToPluginFolder . "/config.php", $config);
        }

        if (!file_exists($pathToPluginFolder . "/data/projects.php")) {
            $projects = '<?php/*|[{"name":"shop","path":"' . $shopwarePath . '"}]|*/?>';
            file_put_contents($pathToPluginFolder . "/data/projects.php", $projects);
        }
        if (!file_exists($pathToPluginFolder . "/data/users.php")) {
            $users = '<?php/*|[{"username":"' . $oDbData["username"] . '","password":"' . sha1(md5($oDbData["password"])) . '","project":"' . $shopwarePath . '"}]|*/?>';
            file_put_contents($pathToPluginFolder . "/data/users.php", $users);
        }
        if (!file_exists($pathToPluginFolder . "/data/active.php")) {
            file_put_contents($pathToPluginFolder . "/data/active.php", '<?php/*|[]|*/?>');
        }

        echo($pluginURL = Shopware()->Container()->getParameter('codiad_i_d_e.plugin_fullurl'));
    }
}