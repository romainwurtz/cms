<?php
/**
 * @link http://buildwithcraft.com/
 * @copyright Copyright (c) 2013 Pixel & Tonic, Inc.
 * @license http://buildwithcraft.com/license
 */

namespace craft\app\controllers;

use Craft;
use craft\app\errors\HttpException;
use craft\app\helpers\JsonHelper;
use craft\app\web\Controller;

Craft::$app->requireEdition(Craft::Pro);

/**
 * The LocalizationController class is a controller that handles various localization related tasks such adding,
 * deleting and re-ordering locales in the control panel.
 *
 * Note that all actions in the controller require an authenticated Craft session via [[Controller::allowAnonymous]].
 *
 * @author Pixel & Tonic, Inc. <support@pixelandtonic.com>
 * @since 3.0
 */
class LocalizationController extends Controller
{
	// Public Methods
	// =========================================================================

	/**
	 * @inheritDoc Controller::init()
	 *
	 * @throws HttpException
	 * @return null
	 */
	public function init()
	{
		// All localization related actions require an admin
		$this->requireAdmin();
	}

	/**
	 * Adds a new a locale.
	 *
	 * @return null
	 */
	public function actionAddLocale()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$localeId = Craft::$app->getRequest()->getRequiredBodyParam('id');
		$success = Craft::$app->getI18n()->addSiteLocale($localeId);
		$this->returnJson(['success' => $success]);
	}

	/**
	 * Saves the new locale order.
	 *
	 * @return null
	 */
	public function actionReorderLocales()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$localeIds = JsonHelper::decode(Craft::$app->getRequest()->getRequiredBodyParam('ids'));
		$success = Craft::$app->getI18n()->reorderSiteLocales($localeIds);
		$this->returnJson(['success' => $success]);
	}

	/**
	 * Deletes a locale.
	 *
	 * @return null
	 */
	public function actionDeleteLocale()
	{
		$this->requirePostRequest();
		$this->requireAjaxRequest();

		$localeId = Craft::$app->getRequest()->getRequiredBodyParam('id');
		$transferContentTo = Craft::$app->getRequest()->getBodyParam('transferContentTo');

		$success = Craft::$app->getI18n()->deleteSiteLocale($localeId, $transferContentTo);
		$this->returnJson(['success' => $success]);
	}
}
