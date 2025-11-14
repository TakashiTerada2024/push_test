<?php

$glob = glob('./qa/phpmetrics/summary/*');
$result = [];
$csvFile = new SplFileObject('php-metrics-logs.csv', 'w');
$csvFile->fputcsv(PhpMetricsDataSet::header());

foreach ($glob as $fileName) {
    $fp = fopen($fileName, "r+");
    $phpMetricsDataSet = one($fileName, $fp);
    $csvFile->fputcsv($phpMetricsDataSet->toArray());
    fclose($fp);
}
exit;

/**
 * one
 *
 * @param string $fileName
 * @param $fp
 * @return PhpMetricsDataSet
 * @author kenji yamamoto <k.yamamoto@balocco.info>
 */
function one(string $fileName, $fp): PhpMetricsDataSet
{
    $metricsDataSet = new PhpMetricsDataSet($fileName);
    $data = [];
    while ($line = fgets($fp)) {
        $line = trim($line);
        $line = preg_replace('/\s\s+/', '[separator]', $line);
        $tmpArray = explode('[separator]', $line);

        if (count($tmpArray) > 1) {
            $metricsDataSet->setter($tmpArray[0], $tmpArray[1]);
        }
    }
    return $metricsDataSet;
}

/**
 *
 */
class PhpMetricsDataSet
{
    private string $fileName;
    private int $linesOfCode;
    private int $logicalLinesOfCode;
    private int $commentLinesOfCode;
    private float $averageVolume;
    private float $averageCommentWeight;
    private float $averageIntelligentContent;
    private int $logicalLinesOfCodeByClass;
    private int $logicalLinesOfCodeByMethod;
    private int $classes;
    private int $interface;
    private int $methods;
    private float $methodsByClass;
    private float $lackOfCohesionOfMethods;
    private float $averageAfferentCoupling;
    private float $averageEfferentCoupling;
    private float $averageInstability;
    private float $depthOfInheritanceTree;
    private int $packages;
    private float $averageClassesPerPackage;
    private float $averageDistance;
    private float $averageIncomingClassDependencies;
    private float $averageOutgoingClassDependencies;
    private float $averageIncomingPackageDependencies;
    private float $averageOutgoingPackageDependencies;
    private float $averageCyclomaticComplexityByClass;
    private float $averageWeightedMethodCountByClass;
    private float $averageRelativeSystemComplexity;
    private float $averageDifficulty;
    private float $averageBugsByClass;
    private float $averageDefectsByClass;
    private int $critical;
    private int $error;
    private int $warning;
    private int $information;

    public function __construct(string $fileName)
    {
        $this->fileName=$fileName;
    }


    static public function header(): array
    {
        return array_keys(get_class_vars(self::class));
    }

    public function setter(string $itemName, $value)
    {
        match ($itemName) {
            'Lines of code' => ($this->linesOfCode = $value),
            'Logical lines of code' => ($this->logicalLinesOfCode = $value),
            'Comment lines of code' => ($this->commentLinesOfCode = $value),
            'Average volume' => ($this->averageVolume = $value),
            'Average comment weight' => ($this->averageCommentWeight = $value),
            'Average intelligent content' => ($this->averageIntelligentContent = $value),
            'Logical lines of code by class' => ($this->logicalLinesOfCodeByClass = $value),
            'Logical lines of code by method' => ($this->logicalLinesOfCodeByMethod = $value),
            'Classes' => ($this->classes = $value),
            'Interface' => ($this->interface = $value),
            'Methods' => ($this->methods = $value),
            'Methods by class' => ($this->methodsByClass = $value),
            'Lack of cohesion of methods' => ($this->lackOfCohesionOfMethods = $value),
            'Average afferent coupling' => ($this->averageAfferentCoupling = $value),
            'Average efferent coupling' => ($this->averageEfferentCoupling = $value),
            'Average instability' => ($this->averageInstability = $value),
            'Depth of Inheritance Tree' => ($this->depthOfInheritanceTree = $value),
            'Packages' => ($this->packages = $value),
            'Average classes per package' => ($this->averageClassesPerPackage = $value),
            'Average distance' => ($this->averageDistance = $value),
            'Average incoming class dependencies' => ($this->averageIncomingClassDependencies = $value),
            'Average outgoing class dependencies' => ($this->averageOutgoingClassDependencies = $value),
            'Average incoming package dependencies' => ($this->averageIncomingPackageDependencies = $value),
            'Average outgoing package dependencies' => ($this->averageOutgoingPackageDependencies = $value),
            'Average Cyclomatic complexity by class' => ($this->averageCyclomaticComplexityByClass = $value),
            'Average Weighted method count by class' => ($this->averageWeightedMethodCountByClass = $value),
            'Average Relative system complexity' => ($this->averageRelativeSystemComplexity = $value),
            'Average Difficulty' => ($this->averageDifficulty = $value),
            'Average bugs by class' => ($this->averageBugsByClass = $value),
            'Average defects by class (Kan)' => ($this->averageDefectsByClass = $value),
            'Critical' => ($this->critical = $value),
            'Error' => ($this->error = $value),
            'Warning' => ($this->warning = $value),
            'Information' => ($this->information = $value),
        };
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
