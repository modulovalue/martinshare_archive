
import UIKit
import MessageUI
import Parse

class SecondViewController: UIViewController, UITextFieldDelegate, LoginProtocol, IsLoggedInProtocol, MFMailComposeViewControllerDelegate {

    @IBOutlet weak var nutzername: UITextField!
    @IBOutlet weak var password: UITextField!
    
    @IBOutlet weak var blurred: UIImageView!
    @IBOutlet var topView: UIView!
    
    override func viewWillAppear(_ animated: Bool) {
        self.navigationController?.navigationBar.isHidden = true
        UIApplication.shared.statusBarStyle = .lightContent
        
        self.nutzername.delegate = self
        self.password.delegate = self
        
        nutzername.text = ""
        password.text = ""
        
        startedChecking()
        
        if(Prefs.getGoodUsername() == "" || Prefs.getKey() == "") {
            isNotLoggedIn()
        } else {
            isLoggedIn()
        }

    }
    
    override func viewDidAppear(_ animated: Bool) {
        //MartinshareAPI.isloggedin(Prefs.getGoodUsername(), key: Prefs.getKey(), ili: self)
    }
    
    @IBAction func homepageBtn(_ sender: AnyObject) {
        UIApplication.shared.openURL(URL(string: "http://www.martinshare.com")!)
    }
    @IBAction func contact(_ sender: AnyObject) {
        
        let emailTitle = "Info"
        let messageBody = " \n \n \n \n Martinshare - iOS"
        let toRecipents = ["info@martinshare.com"]
        let mc: MFMailComposeViewController = MFMailComposeViewController()
        mc.mailComposeDelegate = self
        mc.setSubject(emailTitle)
        mc.setMessageBody(messageBody, isHTML: false)
        mc.setToRecipients(toRecipents)
        
        self.present(mc, animated: true, completion: nil)
    }
    
    func mailComposeController(_ controller:MFMailComposeViewController, didFinishWith result:MFMailComposeResult, error:Error?) {
        
        
        switch result.rawValue {
        case MFMailComposeResult.cancelled.rawValue:
            print("Mail cancelled")
        case MFMailComposeResult.saved.rawValue:
            print("Mail saved")
        case MFMailComposeResult.sent.rawValue:
            
            let alert1 = BPCompatibleAlertController(title: "Erfolg", message: "Die Nachricht wurde erfolgreich verschickt", alertStyle: .alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        case MFMailComposeResult.failed.rawValue:
            
            let alert1 = BPCompatibleAlertController(title: "Fehlgeschlagen", message: "Das Senden der Nachricht ist fehlgeschlagen, bitte probiere es erneut; Fehler: \(error!.localizedDescription)", alertStyle: .alert)
            
            alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
            alert1.presentFrom(self, animated: true, completion: nil)
            
        default:
            break
        }
        self.dismiss(animated: true, completion: nil)
    }
    
    override func touchesBegan(_ touches: Set<UITouch>, with event: UIEvent?) {
        self.view.endEditing(true)
       stopBlur()
    }
    
    func startBlur() {
        UIView.animate(withDuration: 0.2, delay: 0, options: UIViewAnimationOptions.curveEaseOut, animations: {
            
            self.blurred.alpha = 1
            
            }, completion: nil)
    }
    
    func stopBlur() {
        UIView.animate(withDuration: 0.2 , delay: 0, options: UIViewAnimationOptions.curveEaseOut, animations: {
            
            self.blurred.alpha = 0
            
            }, completion: nil)
    }
    @IBAction func editingBegin(_ sender: AnyObject) {
        textFieldSetColor(sender as! UITextField, color: UIColor.black)
        startBlur()
    }

    @IBAction func login(_ sender: AnyObject) {
        login()
    }
        
    func login() {
        
        self.view.endEditing(true)
        stopBlur()
        self.showWaitOverlay()

        MartinshareAPI.loginUser(nutzername.text!, password: password.text!, key: Prefs.getKey(), logIn: self as LoginProtocol)
        
    }
    
    
    func sequeToMainScreen() {
        self.performSegue(withIdentifier: "showMain", sender: nil)
        
    }
    
    
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
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
    
    func textFieldSetColor(_ textField: UITextField, color: UIColor) {
        let newAttribute = NSAttributedString(string: textField.text!, attributes: [NSForegroundColorAttributeName: color])
        textField.attributedText = newAttribute
    }
    
    
    
    
    func animateTextFieldFalscheEingabe(_ textField:UITextField ) {
        
        textFieldSetColor(textField, color: UIColor.red)
        let anim = CABasicAnimation(keyPath: "position")
        anim.duration = 0.047;
        anim.repeatCount = 2;
        anim.autoreverses = true
        anim.fromValue = NSValue(cgPoint: CGPoint(x: textField.center.x - 10, y: textField.center.y))
        anim.toValue = NSValue(cgPoint: CGPoint(x: textField.center.x + 10, y: textField.center.y))
        textField.layer.add(anim, forKey: "position")
    }
    
    func animateTextFieldRichtigeEingabe(_ textField:UITextField ) {
        textFieldSetColor(textField, color: UIColor.black)
    }
    
    
    func enableUserInteraction() {
        topView.isUserInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        topView.isUserInteractionEnabled = false
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
        
        let alert1 = BPCompatibleAlertController(title: "Falsche Nutzerdaten", message: "Bitte überprüfe die eingegebenen Nutzerdaten", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
        
        enableUserInteraction()
    }
    
    func rightCredentials() {
        self.removeAllOverlays()
        
        //animateTextFieldRichtigeEingabe(nutzername)
        //animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        sequeToMainScreen()
    }
    
    func noInternetConnection() {
        self.removeAllOverlays()
        animateTextFieldRichtigeEingabe(nutzername)
        animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        let alert1 = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbindung", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
    }
    
    func unknownError(_ string: String) {
        self.removeAllOverlays()
        animateTextFieldRichtigeEingabe(nutzername)
        animateTextFieldRichtigeEingabe(password)
        enableUserInteraction()
        
        let alert1 = BPCompatibleAlertController(title: "Unbekannter Fehler", message: "Bitte probiere es erneut: \(string)", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
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

    func emptyCredentials(_ goodname: String, key:String)->Bool  {
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

