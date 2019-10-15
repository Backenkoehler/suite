<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Auth\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Auth\AuthRestApiTester;
use Spryker\Glue\AuthRestApi\AuthRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Auth
 * @group RestApi
 * @group AccessTokensForCompanyUserRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class AccessTokensForCompanyUserRestApiCest
{
    /**
     * @var \PyzTest\Glue\Auth\RestApi\AccessTokensForCompanyUserRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(AuthRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(AccessTokensForCompanyUserRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithoutCompanyUser(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithoutCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);
        $I->assertNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForExistingCustomerWithCompanyUser(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithCompanyUser()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $idCompanyUser = current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser'));

        $this->assertResponse($I, HttpCode::CREATED);

        $I->assertNotNull($idCompanyUser);
        $I->assertEquals($idCompanyUser, $this->fixtures->getCompanyUserTransfer()->getUuid());
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForCustomerWithTwoCompanyUserWithoutDefaultOne(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithTwoCompanyUsersWithoutDefaultOne()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $this->assertResponse($I, HttpCode::CREATED);

        $I->assertNull(current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser')));
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS));
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    public function requestAccessTokenForCustomerWithTwoCompanyUserWithDefaultOne(AuthRestApiTester $I): void
    {
        //Act
        $I->sendPOST(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS, [
            'data' => [
                'type' => AuthRestApiConfig::RESOURCE_ACCESS_TOKENS,
                'attributes' => [
                    'username' => $this->fixtures->getCustomerTransferWithTwoCompanyUsersWithDefaultOne()->getEmail(),
                    'password' => AccessTokensRestApiFixtures::TEST_PASSWORD,
                ],
            ],
        ]);

        //Assert
        $idCompanyUser = current($I->grabDataFromResponseByJsonPath('$.data.attributes.idCompanyUser'));

        $this->assertResponse($I, HttpCode::CREATED);

        $I->assertNotNull($idCompanyUser);
        $I->assertEquals($idCompanyUser, $this->fixtures->getDefaultCompanyUserTransfer()->getUuid());
        $I->seeSingleResourceHasSelfLink($I->formatFullUrl(AuthRestApiConfig::RESOURCE_ACCESS_TOKENS));
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     * @param int $responseCode
     *
     * @return void
     */
    protected function assertResponse(AuthRestApiTester $I, int $responseCode): void
    {
        $I->seeResponseCodeIs($responseCode);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
