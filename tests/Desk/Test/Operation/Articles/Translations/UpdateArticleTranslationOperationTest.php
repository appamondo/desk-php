<?php

namespace Desk\Test\Operation\Articles\Translations;

use Desk\Relationship\Resource\Model;
use Desk\Test\Helper\Operation\UpdateOperationTestCase;
use DateTime;
use DateTimeZone;

/**
 * @coversNothing
 * @group system
 */
class UpdateArticleTranslationOperationTest extends UpdateOperationTestCase
{

    /**
     * {@inheritdoc}
     */
    protected function getOperationName()
    {
        return 'UpdateArticleTranslation';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultSystemParameters()
    {
        return array(
            'article_id' => 1,
            'locale' => 'es',
            'body' => '¡Hola!',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function dataParameterValid()
    {
        $body = '{"subject":"Spanish","body":"\\\\u00a1Hola!"}';
        $date = new DateTime('@1234567890', new DateTimeZone('UTC'));

        return array(
            array(
                array(
                    'article_id' => 1,
                    'locale' => 'es',
                    'subject' => 'Spanish',
                    'body' => '¡Hola!',
                ),
                array('body' => "#^$body$#")
            ),
            array(
                array(
                    'article_id' => 2,
                    'locale' => 'de',
                    'publish_at' => $date,
                ),
                array('body' => '#2009-02-13T23:31:30Z#')
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function dataParameterInvalid()
    {
        return array(
            array(
                array(
                    'article_id' => 1,
                )
            ),
            array(
                array(
                    'locale' => 'es',
                )
            ),
            array(
                array(
                    'article_id' => 1,
                    'locale' => 'es',
                    'outdated' => 'foo',
                )
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function assertSystem(Model $articleTranslation)
    {
        $this->assertSame('ArticleTranslationModel', $articleTranslation->getStructure()->getName());

        $this->assertSame('Spanish Translation', $articleTranslation->get('subject'));
        $this->assertSame('es', $articleTranslation->get('locale'));
    }

    /**
     * {@inheritdoc}
     */
    public function dataLinksAdditional()
    {
        return array(
            array('article', 'ShowArticle', array('id' => 1)),
        );
    }
}
