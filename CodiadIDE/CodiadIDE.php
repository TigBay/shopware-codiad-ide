<?php

namespace CodiadIDE;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CodiadIDE extends Plugin
{

    public function install(Plugin\Context\InstallContext $context)
    {
        $pathToPluginFolder = $this->getPath()."/codiad";

        if (file_exists($pathToPluginFolder . "/config.php")) {
            unlink($pathToPluginFolder . "/config.php");
        }

        if (file_exists($pathToPluginFolder . "/data/projects.php")) {
            unlink($pathToPluginFolder . "/data/projects.php");
        }

        if (file_exists($pathToPluginFolder . "/data/users.php")) {
            unlink($pathToPluginFolder . "/data/users.php");
        }

        if (file_exists($pathToPluginFolder . "/data/active.php")) {
            unlink($pathToPluginFolder . "/data/active.php");
        }

        if (file_exists($pathToPluginFolder . "/data/settings.php")) {
            unlink($pathToPluginFolder . "/data/settings.php");
        }

        return true;
    }

    /**
     * Builds the Plugin.
     *
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $isHttps = isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN);

        $httpOrHttps = ($isHttps) ? "https://" : "http://";

        $shopwarePath = $container->getParameter('kernel.root_dir');
        $pluginPath = $this->getPath();
        $FullPluginPath = rtrim(str_replace("shopware.php", "", $_SERVER['SCRIPT_FILENAME']), "/").str_replace($shopwarePath, "" , $pluginPath )."/codiad";

        if(isset($_SERVER['HTTP_HOST'])){
            $pluginURL = str_replace($shopwarePath, $_SERVER['HTTP_HOST'] , $pluginPath )."/codiad";
        }else{
            $pluginURL = "";
        }

        $container->setParameter($this->getContainerPrefix() . '.plugin_url', $pluginURL);
        $container->setParameter($this->getContainerPrefix() . '.plugin_fullurl', $httpOrHttps.$pluginURL);
        $container->setParameter($this->getContainerPrefix() . '.plugin_fullpath', $FullPluginPath);
    }


    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Backend' => 'onBackendPostDispatch'
        ];
    }

    public function uninstall(Plugin\Context\UninstallContext $context)
    {
        return true;
    }

    public function update(Plugin\Context\UpdateContext $context)
    {
        return true;
    }

    /**
     * enable method
     * activates the menu entry
     *
     * @return array
     */
    public function enable()
    {
        $sql = "UPDATE `s_core_menu`
                SET `active` = 1
                WHERE `Controller` = 'Codiad';";
        Shopware()->Db()->query($sql);

        return array('success' => true, 'invalidateCache' => array('frontend', 'template', 'config', 'backend'));
    }

    /**
     * disable method
     * deactivates the menu entry
     *
     * @return array
     */
    public function disable()
    {
        $sql = "UPDATE `s_core_menu`
                SET `active` = 0
                WHERE `Controller` = 'Codiad';";
        Shopware()->Db()->query($sql);

        return array('success' => true, 'invalidateCache' => array('frontend', 'template', 'config', 'backend'));
    }

    public function onBackendPostDispatch(\Enlight_Event_EventArgs $args)
    {

        //$config = $this->Config();

        /** @var \Enlight_Controller_Action $controller */
        /*$controller = $args->getSubject();
        //$view = $controller->View();
        $request = $controller->Request();

        if ($request->getActionName() == 'index' && !empty($config->secom_ips)) {
            $sql = "UPDATE `s_core_menu`
                                SET `active` = %s
                            WHERE `Controller` = 'Codiad';";

            $tmpAIps = explode(PHP_EOL, $config->secom_ips);
            $activeFlag = false;

            foreach($tmpAIps as $sIp){
                if ($_SERVER['REMOTE_ADDR'] == $sIp) {
                    $this->db->query(sprintf($sql, "1"));
                    $activeFlag = true;
                }
            }

            if(false==$activeFlag){
                $this->db->query(sprintf($sql, "0"));
            }
        }
        */
    }

}
