<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    
    // alternative to CLI arguments, easier to maintain and extend
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);
    
    // run single rule only on specific path
    $parameters->set(Option::ONLY, [
        ArraySyntaxFixer::class => [__DIR__ . '/src/NewCode'],
    ]);
    
    $parameters->set(Option::SKIP, [
        // skip paths with legacy code
        __DIR__ . '/packages/*/src/Legacy',
        
        ArraySyntaxFixer::class => [
            // path to file (you can copy this from error report)
            __DIR__ . '/packages/EasyCodingStandard/packages/SniffRunner/src/File/File.php',
            
            // or multiple files by path to match against "fnmatch()"
            __DIR__ . '/packages/*/src/Command',
        ],
        
        // skip rule completely
        ArraySyntaxFixer::class,
        
        // just single one part of the rule?
        ArraySyntaxFixer::class . '.SomeSingleOption',
        
        // ignore specific error message
        'Cognitive complexity for method "addAction" is 13 but has to be less than or equal to 8.',
    ]);
    
    // scan other file extendsions; [default: [php]]
    $parameters->set(Option::FILE_EXTENSIONS, ['php', 'phpt']);
    
    // configure cache paths & namespace - useful for Gitlab CI caching, where getcwd() produces always different path
    // [default: sys_get_temp_dir() . '/_changed_files_detector_tests']
    $parameters->set(Option::CACHE_DIRECTORY, '.ecs_cache');
    
    // [default: \Nette\Utils\Strings::webalize(getcwd())']
    $parameters->set(Option::CACHE_NAMESPACE, 'my_project_namespace');
    
    // indent and tabs/spaces
    // [default: spaces]
    $parameters->set(Option::INDENTATION, 'tab');
    
    // [default: PHP_EOL]; other options: "\n"
    $parameters->set(Option::LINE_ENDING, "\r\n");
};