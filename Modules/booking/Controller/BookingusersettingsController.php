<?php

require_once 'Framework/Controller.php';
require_once 'Framework/Form.php';

require_once 'Modules/core/Model/CoreUserSettings.php';
require_once 'Modules/core/Controller/CoresecureController.php';

require_once 'Modules/booking/Model/BookingTranslator.php';

/**
 * Controller to edit user settings
 * 
 * @author sprigent
 *
 */
class BookingusersettingsController extends CoresecureController {

    /**
     * Constructor
     */
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    /**
     * (non-PHPdoc)
     * @see Controller::index()
     */
    public function indexAction() {

        $user_id = $this->request->getSession()->getAttribut("id_user");
        $userSettingsModel = new CoreUserSettings();
        $calendarDefaultView = $userSettingsModel->getUserSetting($user_id, "calendarDefaultView");
        
        $lang = $this->getLanguage();
        $form = new Form($this->request, "bokkingusersettingsform");
        $form->setTitle(BookingTranslator::Calendar_Default_view($lang));
        
        $choicesview = array(BookingTranslator::Day($lang), BookingTranslator::Day_Area($lang), BookingTranslator::Week($lang), BookingTranslator::Week_Area($lang));
        $choicesidview = array("bookingday", "bookingdayarea", "bookingweek", "bookingweekarea");
        $form->addSelect("calendarDefaultView", BookingTranslator::Default_view($lang), $choicesview, $choicesidview, $calendarDefaultView);
        
        $form->setButtonsWidth(4, 8);
        $form->setValidationButton(CoreTranslator::Ok($lang), "bookingusersettings");
        $form->setCancelButton(CoreTranslator::Cancel($lang), "coresettings");
        
        if ($form->check()){
            $calendarDefaultView = $this->request->getParameter("calendarDefaultView");

            $user_id = $this->request->getSession()->getAttribut("id_user");

            $userSettingsModel = new CoreUserSettings();
            $userSettingsModel->setSettings($user_id, "calendarDefaultView", $calendarDefaultView);

            $userSettingsModel->updateSessionSettingVariable();

            $this->redirect("bookingusersettings");
        }
        
        
        $this->render(array(
            'lang' => $this->getLanguage(),
            'form' => $form->getHtml($lang)
        ));
    }

}
