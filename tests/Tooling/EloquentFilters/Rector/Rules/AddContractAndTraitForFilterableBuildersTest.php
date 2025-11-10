<?php

declare(strict_types=1);

namespace Tests\Tooling\EloquentFilters\Rector\Rules;

use Tests\TestCase;
use PhpParser\ParserFactory;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Tooling\EloquentFilters\Rector\Rules\AddContractAndTraitForFilterableBuilders;
use PhpParser\Parser;

#[CoversClass(AddContractAndTraitForFilterableBuilders::class)]
class AddContractAndTraitForFilterableBuildersTest extends TestCase
{
    private AddContractAndTraitForFilterableBuilders $rule;

    private Parser|ParserFactory $parser;

    protected function setUp(): void
    {
        $this->rule = app(AddContractAndTraitForFilterableBuilders::class);
        $this->parser = (new ParserFactory)->createForNewestSupportedVersion();
    }

    private function getFixture(string $filename): string
    {
        return file_get_contents(__DIR__.'/../../../../Fixtures/Variations/'.$filename);
    }

    #[Test]
    public function adds_both_contract_and_trait_when_filter_attribute_is_used(): void
    {
        $code = $this->getFixture('UserBuilderOnlyFilters.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        // Debug: Check if the class extends Builder
        $this->assertNotNull($classNode->extends, 'Class should extend Builder');
        $this->assertEquals('Builder', $classNode->extends->toString());

        // Check if Filter attribute is detected
        $hasFilterAttributes = $this->hasFilterAttributes($classNode);
        $this->assertTrue($hasFilterAttributes, 'Filter attributes should be detected');

        $result = $this->rule->refactor($classNode);

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($this->rule->implementsFilterableContract($result));
        $this->assertTrue($this->rule->usesHasFiltersTrait($result));
    }

    #[Test]
    public function adds_contract_when_trait_is_used(): void
    {
        $code = $this->getFixture('UserBuilderNoContract.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($this->rule->implementsFilterableContract($result));
        $this->assertTrue($this->rule->usesHasFiltersTrait($result));
    }

    #[Test]
    public function adds_trait_when_contract_is_implemented(): void
    {
        $code = $this->getFixture('UserBuilderNoTrait.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertInstanceOf(Class_::class, $result);
        $this->assertTrue($this->rule->implementsFilterableContract($result));
        $this->assertTrue($this->rule->usesHasFiltersTrait($result));
    }

    #[Test]
    public function does_not_modify_complete_class(): void
    {
        $code = $this->getFixture('UserBuilder.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNull($result); // Should not modify already complete class
    }

    #[Test]
    public function does_not_modify_non_builder_class(): void
    {
        $code = $this->getFixture('NonBuilderClass.php');

        $nodes = $this->parser->parse($code);
        $classNode = $this->getClassNode($nodes);

        $this->assertNotNull($classNode, 'Should find a class node');

        $result = $this->rule->refactor($classNode);

        $this->assertNull($result); // Should not modify non-Builder classes
    }

    private function hasFilterAttributes(Class_ $node): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof \PhpParser\Node\Stmt\ClassMethod) {
                if ($this->rule->methodHasFilterAttribute($stmt)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function getClassNode(array $nodes): ?Class_
    {
        foreach ($nodes as $node) {
            if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
                foreach ($node->stmts as $stmt) {
                    if ($stmt instanceof Class_) {
                        return $stmt;
                    }
                }
            }
        }

        return null;
    }
}
