<?php

declare(strict_types=1);

namespace App\Behat;

use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Mink;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behatch\Context\JsonContext as JsonContextBehatch;
use Behatch\Context\RestContext;
use Coduo\PHPMatcher\PHPMatcher;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Webmozart\Assert\Assert;

class JsonContext implements MinkAwareContext
{
    private RestContext $restContext;
    private JsonContextBehatch $jsonContext;
    private ArrayContainsComparator $arrayContainsComparator;
    private ?Mink $mink;
    private array $minkParameters;

    public function __construct()
    {
        $this->arrayContainsComparator = new ArrayContainsComparator();
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        /**
         * @var InitializedContextEnvironment $environment
         */
        $environment = $scope->getEnvironment();

        /**
         * @var RestContext $restContext
         */
        $restContext = $environment->getContext(RestContext::class);

        $this->restContext = $restContext;
        $session = $this->restContext->getMink()->getSession();
        $session->reset();

        /**
         * @var JsonContextBehatch $jsonContext
         */
        $jsonContext = $environment->getContext(JsonContextBehatch::class);
        $this->jsonContext = $jsonContext;

        /**
         * @var KernelBrowser $client
         * @psalm-suppress UndefinedInterfaceMethod
         */
        $client = $session->getDriver()->getClient();
        $client->disableReboot();
    }

    /**
     * Sets Mink instance.
     *
     * @param Mink $mink Mink session manager
     */
    public function setMink(Mink $mink): void
    {
        $this->mink = $mink;
    }

    /**
     * Sets parameters provided for Mink.
     */
    public function setMinkParameters(array $parameters): void
    {
        $this->minkParameters = $parameters;
    }

    /**
     * @Then the JSON should be partially equal to:
     */
    public function theJsonShouldBePartiallyEqualTo(PyStringNode $contains): void
    {
        $actualResponse = $this->mink->getSession()->getPage()->getContent();

        $contains = $this->jsonDecode((string) $contains);
        $body = $this->jsonDecode($actualResponse);

        $this->arrayContainsComparator = new ArrayContainsComparator();
        try {
            // Compare the arrays, on error this will throw an exception
            Assert::true($this->arrayContainsComparator->compare($contains, $body));
        } catch (InvalidArgumentException $e) {
            throw new RuntimeException('Comparator did not return in a correct manner. Marking assertion as failed.');
        }
    }

    /**
     * Convert some variable to a JSON-array.
     *
     * @throws InvalidArgumentException
     */
    private function jsonDecode(string $value, string $errorMessage = null): array
    {
        $decoded = json_decode($value, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException($errorMessage ?: 'The supplied parameter is not a valid JSON object.');
        }

        return (array) $decoded;
    }

    /**
     * @Then JSON node :node should not contain value :text
     */
    public function theJsonNodeShouldNotContain($node, $text): void
    {
        $json = $this->jsonContext->theJsonNodeShouldExist($node);

        Assert::notContains(json_encode($json), $text);
    }

    /**
     * @When I send a :method request to :url with JSON headers
     */
    public function iSendaRequestTo(string $method, string $url): void
    {
        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->restContext->iAddHeaderEqualTo('Accept', 'application/json');
        $this->restContext->iSendARequestTo($method, $url);
    }

    /**
     * @When I send a :method request to :url with JSON headers and body:
     */
    public function iSendaRequestWithBodyTo(string $method, string $url, PyStringNode $body): void
    {
        $this->restContext->iAddHeaderEqualTo('Content-Type', 'application/json');
        $this->restContext->iAddHeaderEqualTo('Accept', 'application/json');
        $this->restContext->iSendARequestTo($method, $url, $body);
    }

    /**
     * @Then the json response should match:
     */
    public function theJsonResponseShouldMatch(PyStringNode $json): void
    {
        $actualResponse = $this->mink->getSession()->getPage()->getContent();
        $expectedResponse = $json->getRaw();
        $expectedResponse = str_replace("\n", '', $expectedResponse);

        $this->match($actualResponse, $expectedResponse);
    }

    private function match(string $actual, string $expected): void
    {
        $matcher = new PHPMatcher();

        $match = $matcher->match($actual, $expected);
        if (!$match) {
            $error = "---------------------------\n";
            $error .= 'Error (actual first, expected second): '.$matcher->error()."\n";
            $error .= "Actual json:\n".json_encode(json_decode($actual, true), JSON_PRETTY_PRINT)."\n";
            $error .= "---------------------------\n";

            throw new ExpectationException($error, $this->mink->getSession());
        }
    }
}
