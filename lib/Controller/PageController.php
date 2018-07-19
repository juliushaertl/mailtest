<?php
namespace OCA\MailTest\Controller;

use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\IL10N;
use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\Mail\IEMailTemplate;
use OCP\Mail\IMailer;

class PageController extends Controller {
	private $userId;

	public function __construct($AppName, IRequest $request, $UserId, IL10N $l10n){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->l10n = $l10n;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		$response = new TemplateResponse('mailtest', 'index', []);
		$policy = new ContentSecurityPolicy();
		$policy->addAllowedChildSrcDomain('*');
		$policy->addAllowedFrameDomain('*');
		$policy->addAllowedFrameAncestorDomain('*');
		$response->setContentSecurityPolicy($policy);
		return $response;
	}
	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function mail() {
		$displayName = 'John Doe';
		$calendarName = 'Example Calendar';
		$url = 'https://';
		/** @var IMailer $mailer */
		$mailer = \OC::$server->query(IMailer::class);
		$emailTemplate = $mailer->createEMailTemplate('calendar.PublicShareNotification', [
			'recipient' => 'example@example.com',
			'displayname' => $displayName,
			'calendar_name' => $calendarName,
			'calendar_url' => 'https://',
		]);

		$emailTemplate->addHeader();
		$emailTemplate->addHeading($this->l10n->t('%s has published the calendar »%s«', [$displayName, $calendarName]));

		$emailTemplate->addBodyText($this->l10n->t('Hello,'));
		$emailTemplate->addBodyText($this->l10n->t('We wanted to inform you that %s has published the calendar »%s«.', [$displayName, $calendarName]));

		$emailTemplate->addBodyButton($this->l10n->t('Open »%s«', [$calendarName]), $url);
		$emailTemplate->addBodyButtonGroup($this->l10n->t('More info »%s«', [$calendarName]), $url,$this->l10n->t('More info »%s«', [$calendarName]), $url);
		// TRANSLATORS term at the end of a mail
		$emailTemplate->addBodyText($this->l10n->t('Cheers!'));

		$emailTemplate->addFooter();

		$bodyHTML = $emailTemplate->renderHtml();
		$textBody = $emailTemplate->renderText();

		$response = new TemplateResponse('mailtest', 'mail', ['mail' => $bodyHTML], 'blank');  // templates/index.php
		$policy = new ContentSecurityPolicy();
		$policy->addAllowedChildSrcDomain('*');
		$policy->addAllowedFrameAncestorDomain('*');
		$policy->addAllowedFrameDomain('*');
		$response->setContentSecurityPolicy($policy);
		return $response;
	}

}
