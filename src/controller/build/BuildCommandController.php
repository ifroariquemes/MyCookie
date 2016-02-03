<?php

namespace controller\build;

class BuildCommandController
{

    public static function createJSBundle()
    {
        $check = 0;
        system(sprintf("node %s -o %s", BuildFileController::RJSFile, BuildFileController::BuildJSFile), $check);
        if ($check !== 0) {
            system(sprintf("nodejs %s -o %s", BuildFileController::RJSFile, BuildFileController::BuildJSFile), $check);
            if ($check !== 0) {
                global $_MyCookie;
                $_MyCookie->loadView('build', 'node-run');
            }
        }
    }

    public static function createCSSBundle()
    {
        $check = 0;
        system(sprintf("node %s -o %s", BuildFileController::RJSFile, BuildFileController::BuildCSSFile), $check);
        if ($check !== 0) {
            system(sprintf("nodejs %s -o %s", BuildFileController::RJSFile, BuildFileController::BuildCSSFile), $check);
            if ($check !== 0) {
                global $_MyCookie;
                $_MyCookie->loadView('build', 'node-run');
            }
        }
    }

    public static function generatePortableObject($file)
    {
        $check = 1;
        system(sprintf('xgettext -d %s -p %s/_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildFileController::SourceLang, BuildFileController::SourceViews, $file), $check);
        if ($check !== 0) {
            system(sprintf('gettext -d %s -p %s/_po -k__ -k_e -k_n:1,2 -k_en:1,2 %s/%s/*.php', $file, BuildFileController::SourceLang, BuildFileController::SourceViews, $file), $check);
            if ($check !== 0) {
                global $_MyCookie;
                $_MyCookie->loadView('build', 'xgettext-run');
                return false;
            }
        }
        return true;
    }

    public static function generateMachineObject($file)
    {
        $check = 0;
        $filePath = explode('.', $file);
        system(sprintf('msgfmt %s -o %s.mo', $file, $filePath[0]), $check);
        if ($check > 1) {
            global $_MyCookie;
            $_MyCookie->loadView('build', 'msgfmt-run');
            return false;
        }
        return true;
    }

    public static function updateDatabase()
    {
        global $_EntityManager;
        global $_Server;
        try {
            $_EntityManager->beginTransaction();
            $_EntityManager->close();
            if ($_Server->getOS() === \lib\util\Server::OS_WINDOWS) {
                system('"vendor/bin/doctrine.bat" orm:schema-tool:update --force');
            } else {
                system('php vendor/bin/doctrine orm:schema-tool:update --force');
            }
        } catch (\PDOException $e) {
            echo utf8_encode($e->getMessage());
        }
    }

    public static function recreateDatabase()
    {
        global $_Server;
        if ($_Server->getOS() === \lib\util\Server::OS_WINDOWS) {
            system('"vendor/bin/doctrine.bat" orm:schema-tool:drop --force');
            system('"vendor/bin/doctrine.bat" orm:schema-tool:create');
        } else {
            system('php vendor/bin/doctrine orm:schema-tool:drop --force');
            system('php vendor/bin/doctrine orm:schema-tool:create');
        }
    }

}
