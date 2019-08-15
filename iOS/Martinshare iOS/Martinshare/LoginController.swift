
import UIKit
import MessageUI

class SecondViewController: UIViewController, UITextFieldDelegate, LoginProtocol, IsLoggedInProtocol, MFMailComposeViewControllerDelegate {

    @IBOutlet weak var nutzername: UITextField!
    @IBOutlet weak var password: UITextField!
    
    @IBOutlet weak var blurred: UIImageView!
    @IBOutlet var topView: UIView!
    
    override func viewWillAppear(animated: Bool) {
        self.navigationController?.navigationBar.hidden = true
    }
    override func viewDidLoad() {

        UIApplication.sharedApplication().statusBarStyle = .LightContent

        self.nutzername.delegate = self
        self.password.delegate = self
        nutzername.text = ""
        password.text = ""
        MartinshareAPI.isloggedin(Prefs.getGoodUsername(), key: Prefs.getKey(), ili: self)
    }
    
    @IBAction func homepageBtn(sender: AnyObject) {
        UIApplication.sharedApplication().openURL(NSURL(string: "http://www.martinshare.com")!)
    }
    @IBAction func contact(sender: AnyObject) {
        
        var emailTitle = "Info"
        var messageBody = " \n \n \n \n Martinshare - iOS"
        var toRecipents = ["info@martinshare.com"]
        var mc: MFMailComposeViewController = MFMailComposeViewController()
        mc.mailComposeDelegate = self
        mc.setSubject(emailTitle)
        mc.setMessageBody(messageBody, isHTML: false)
        mc.setToRecipients(toRecipents)
        
        self.presentViewController(mc, animated: true, completion: nil)
    }
    
    func mailComposeController(controller:MFMailComposeViewController, didFinishWithResult result:MFMailComposeResult, error:NSError) {
        switch result.value {
        case MFMailComposeResultCancelled.value:
            println("Mail cancelled")
        case MFMailComposeResultSaved.value:
            println("Mail saved")
        case MFMailComposeResultSent.value:
            
            var alert1 = BPCompatibleAlertController(title: "Erfolg", message: "Die Nachricht wurde erfolgreich verschickt", alertStyle: .Alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        case MFMailComposeResultFailed.value:
            
            var alert1 = BPCompatibleAlertController(title: "Fehlgeschlagen", message: "Das Senden der Nachricht ist fehlgeschlagen, bitte probiere es erneut; Fehler: \(error.localizedDescription)", alertStyle: .Alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        default:
            break
        }
        self.dismissViewControllerAnimated(true, completion: nil)
    }
    
    override func touchesBegan(touches: Set<NSObject>, withEvent event: UIEvent) {
        self.view.endEditing(true)
       stopBlur()
    }
    
    func startBlur() {
        UIView.animateWithDuration(0.2, delay: 0, options: UIViewAnimationOptions.CurveEaseOut, animations: {
            
            self.blurred.alpha = 1
            
            }, completion: nil)
    }
    
    func stopBlur() {
        UIView.animateWithDuration(0.2 , delay: 0, options: UIViewAnimationOptions.CurveEaseOut, animations: {
            
            self.blurred.alpha = 0
            
            }, completion: nil)
    }
    @IBAction func editingBegin(sender: AnyObject) {
        textFieldSetColor(sender as! UITextField, color: UIColor.blackColor())
        startBlur()
    }

    @IBAction func login(sender: AnyObject) {
        login()
    }
        
    func login() {
        
        self.view.endEditing(true)
        stopBlur()
        self.showWaitOverlay()

        MartinshareAPI.loginUser(nutzername.text, password: password.text, key: Prefs.getKey(), logIn: self as LoginProtocol)
        
    }
    
    
    func sequeToMainScreen() {
        print("SEGUEEEEEEE")
        //dispatch_async(dispatch_get_main_queue(), {
            self.performSegueWithIdentifier("showMain", sender: nil)
        //})
    }
    
    
    func textFieldShouldReturn(textField: UITextField) -> Bool {
        switch(textField) {
            case nutzername:
                password.becomeFirstResponder()
            case password:
                resignFirstResponder()
                login()
            default:
                 break
        }
        return true
    }
    
    func textFieldSetColor(textField: UITextField, color: UIColor) {
        var newAttribute = NSAttributedString(string: textField.text, attributes: [NSForegroundColorAttributeName: color])
        textField.attributedText = newAttribute
    }
    
    
    
    
    func animateTextFieldFalscheEingabe(textField:UITextField ) {
        
        textFieldSetColor(textField, color: UIColor.redColor())
        let anim = CABasicAnimation(keyPath: "position")
        anim.duration = 0.047;
        anim.repeatCount = 2;
        anim.autoreverses = true
        anim.fromValue = NSValue(CGPoint: CGPointMake(textField.center.x - 10, textField.center.y))
        anim.toValue = NSValue(CGPoint: CGPointMake(textField.center.x + 10, textField.center.y))
        textField.layer.addAnimation(anim, forKey: "position")
    }
    
    func animateTextFieldRichtigeEingabe(textField:UITextField ) {
        textFieldSetColor(textField, color: UIColor.blackColor())
    }
    
    
    func enableUserInteraction() {
        topView.userInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        topView.userInteractionEnabled = false
    }
    
    
    
    func startedLogingIn() {
        self.showWaitOverlay()
        disableUserInteraction()
    }
    
    func wrongCredentials() {
        self.removeAllOverlays()
        print("falsche eingabe")
        animateTextFieldFalscheEingabe(nutzername)
        animateTextFieldFalscheEingabe(password)
        
        var alert1 = BPCompatibleAlertController(title: "Falsche Nutzerdaten", message: "Bitte überprüfe die eingegebenen Nutzerdaten", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func rightCredentials() {
        self.removeAllOverlays()
        
        animateTextFieldRichtigeEingabe(nutzername)
        animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        sequeToMainScreen()
    }
    
    func noInternetConnection() {
        self.removeAllOverlays()
        animateTextFieldRichtigeEingabe(nutzername)
        animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        var alert1 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbindung", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
    }
    
    func unknownError() {
        self.removeAllOverlays()
        animateTextFieldRichtigeEingabe(nutzername)
        animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        var alert1 = BPCompatibleAlertController(title: "Unbekannter Fehler", message: "Bitte probiere es erneut", alertStyle: .Alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.Default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
    }
    
    
    
    func startedChecking() {
        self.showWaitOverlay()
        disableUserInteraction()
    }
    
    func isLoggedIn() {
        self.removeAllOverlays()
        enableUserInteraction()
        sequeToMainScreen()
    }

    func isNotLoggedIn() {
        self.removeAllOverlays()
        enableUserInteraction()
    }

    func emptyCredentials(goodname: String, key:String)->Bool  {
        if (goodname == "" && key == "") {
            return true
        } else {
            return false
        }
    }

    func neverWasLoggedIn() {
        self.removeAllOverlays()
        enableUserInteraction()
    }
    
}

