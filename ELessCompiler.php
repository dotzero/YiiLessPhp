<?php
/**
 * ELessCompiler class file.
 *
 * @package ELessCompiler
 * @version 1.0
 * @author dotzero <mail@dotzero.ru>
 * @link http://www.dotzero.ru/
 * @link https://github.com/dotzero/yii-less
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * ELessPhp is an extension for the Yii PHP framework that allows developers to compile LESS files into CSS
 * on the fly, using the LessPhp compiler.
 *
 * Requirements:
 * - Yii Framework 1.1.14 or above
 *
 * Installation:
 *
 * - Add vendor path to your configuration file, attach component and set properties:
 * 'aliases' => array(
 *      ...
 *      'vendor' => realpath(__DIR__ . '/../../vendor'),
 * ),
 * 'components' => array(
 *      ...
 *      'less' => array(
 *          'class' => 'vendor.dotzero.yii-less.ELessCompiler',
 *          'lessphpDir' => 'vendor.leafo.lessphp', // Path alias of lessc.inc.php directory
 *          'forceCompile' => false, // Force recompile LESS into CSS every initializes the component
 *          'files' => array( // Files to compile (relative from your base path)
 *              'css/style.less' => 'css/style.css',
 *              'css/userstyle.less' => 'css/userstyle.css',
 *          ),
 *      ),
 * ),
 *
 * - Add the following to your config file `preload` section:
 * 'preload' => array(
 *      ...
 *      'less',
 * ),
 */
class ELessCompiler extends CApplicationComponent
{
    /**
     * @var string Path alias of the directory where the lessc.inc.php file can be found
     */
    public $lessphpDir = 'vendor.leafo.lessphp';

    /**
     * @var bool Force recompile LESS into CSS every initializes the component
     */
    public $forceCompile = false;

    /**
     * @var array List of the LESS files to compile into CSS
     */
    public $files = array();

    /**
     * @var null|string Absolute path to application the base path
     */
    private $basePath = null;

    /**
     * @var null|lessc Instance of LessPhp compiler
     */
    private $lessphp = null;

    /**
     * Initializes the application component.
     *
     * @throws CException
     */
    public function init()
    {
        parent::init();

        // adding LessPhp library directory to include path
        Yii::import($this->lessphpDir . '.*');

        // including LessPhp class
        require_once('lessc.inc.php');

        if ($this->basePath === null) {
            $this->basePath = Yii::getPathOfAlias('webroot');
        }

        if (!is_array($this->files)) {
            throw new CException('Failed to compile LESS. Property files must be an array.');
        }

        foreach ($this->files AS $fileLess => $fileCss) {
            $pathLess = $this->basePath . '/' . $fileLess;
            $pathCss = $this->basePath . '/' . $fileCss;

            try {
                if (file_exists($pathLess)) {
                    if ($this->forceCompile === true) {
                        $this->getLessphp()->compileFile($pathLess, $pathCss);
                    } else {
                        $this->getLessphp()->checkedCompile($pathLess, $pathCss);

                    }
                }
            } catch (Exception $e) {
                throw new CException(__CLASS__ . ': ' . Yii::t(
                        'less',
                        'Failed to compile less file with message: `{message}`.',
                        array('{message}' => $e->getMessage())
                    ));
            }
        }
    }

    /**
     * Return LessPhp instance
     *
     * @return lessc
     */
    public function getLessphp()
    {
        if ($this->lessphp === null) {
            $this->lessphp = new lessc();
        }

        return $this->lessphp;
    }
}
