<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Auth\RestApi;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Auth\AuthRestApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class AccessTokensForCompanyUserRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_PASSWORD = 'Test password';

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $defaultCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithoutCompanyUser;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithTwoCompanyUsersWithoutDefaultOne;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransferWithTwoCompanyUsersWithDefaultOne;

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(AuthRestApiTester $I): FixturesContainerInterface
    {
        $I->haveCompanyMailConnectorToMailDependency();

        $this->initCustomerTransferWithCompanyUser($I);
        $this->initCustomerTransferWithoutCompanyUser($I);
        $this->initCustomerTransferWithTwoCompanyUsersWithoutDefaultOne($I);
        $this->initCustomerTransferWithTwoCompanyUsersWithDefaultOne($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithoutCompanyUser(): CustomerTransfer
    {
        return $this->customerTransferWithoutCompanyUser;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithTwoCompanyUsersWithoutDefaultOne(): CustomerTransfer
    {
        return $this->customerTransferWithTwoCompanyUsersWithoutDefaultOne;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransferWithTwoCompanyUsersWithDefaultOne(): CustomerTransfer
    {
        return $this->customerTransferWithTwoCompanyUsersWithDefaultOne;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserTransfer(): CompanyUserTransfer
    {
        return $this->companyUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getDefaultCompanyUserTransfer(): CompanyUserTransfer
    {
        return $this->defaultCompanyUserTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    protected function initCustomerTransferWithCompanyUser(AuthRestApiTester $I): void
    {
        $customerTransfer = $this->createCustomerTransfer($I);
        $companyUserTransfer = $this->createCompanyUserTransfer($I, $customerTransfer);

        $this->companyUserTransfer = $companyUserTransfer;
        $this->customerTransferWithCompanyUser = $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    protected function initCustomerTransferWithoutCompanyUser(AuthRestApiTester $I): void
    {
        $this->customerTransferWithoutCompanyUser = $this->createCustomerTransfer($I);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    protected function initCustomerTransferWithTwoCompanyUsersWithoutDefaultOne(AuthRestApiTester $I): void
    {
        $customerTransfer = $this->createCustomerTransfer($I);

        $this->createCompanyUserTransfer($I, $customerTransfer);
        $this->createCompanyUserTransfer($I, $customerTransfer);

        $this->customerTransferWithTwoCompanyUsersWithoutDefaultOne = $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return void
     */
    protected function initCustomerTransferWithTwoCompanyUsersWithDefaultOne(AuthRestApiTester $I): void
    {
        $customerTransfer = $this->createCustomerTransfer($I);

        $this->defaultCompanyUserTransfer = $this->createCompanyUserTransfer($I, $customerTransfer, [
            CompanyUserTransfer::IS_DEFAULT => true,
        ]);
        $this->createCompanyUserTransfer($I, $customerTransfer);

        $this->customerTransferWithTwoCompanyUsersWithDefaultOne = $customerTransfer;
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(AuthRestApiTester $I): CustomerTransfer
    {
        return $I->haveCustomer([
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
    }

    /**
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(AuthRestApiTester $I, CustomerTransfer $customerTransfer, array $seed = []): CompanyUserTransfer
    {
        $companyTransfer = $I->haveActiveCompany([
            CompanyTransfer::STATUS => 'approved',
        ]);

        return $I->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->createCompanyBusinessUnit($companyTransfer, $I)->getIdCompanyBusinessUnit(),
        ] + $seed);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \PyzTest\Glue\Auth\AuthRestApiTester $I
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createCompanyBusinessUnit(CompanyTransfer $companyTransfer, AuthRestApiTester $I): CompanyBusinessUnitTransfer
    {
        return $I->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
    }
}
