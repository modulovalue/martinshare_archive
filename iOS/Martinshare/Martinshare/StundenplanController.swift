//
//  StundenplanController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 26.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//
import UIKit
import Foundation



class StundenplanController : UIViewController, UIScrollViewDelegate, ConnectionProtocol {
   
    // Get the documents Directory
    static func documentsDirectory() -> String {
        let documentsFolderPath = NSSearchPathForDirectoriesInDomains(FileManager.SearchPathDirectory.documentDirectory, FileManager.SearchPathDomainMask.userDomainMask, true)[0] 
        return documentsFolderPath
    }
    
    // Get path for a file in the directory
    static func fileInDocumentsDirectory(_ filename: String) -> String {
        return (documentsDirectory() as NSString).appendingPathComponent(filename)
    }
    
    // Define the specific path, image name
    // static let imagePath = self.fileInDocumentsDirectory(MartinshareAPI.stundenplanName)
    static func saveImage (_ image: UIImage, path: String ) -> Bool{
        let pngImageData = UIImagePNGRepresentation(image)
        //let jpgImageData = UIImageJPEGRepresentation(image, 1.0)   // if you want to save as JPEG
        let result = (try? pngImageData!.write(to: URL(fileURLWithPath: path), options: [.atomic])) != nil
        return result
    }
    
    static func loadImageFromPath(_ path: String) -> UIImage? {
        let image = UIImage(contentsOfFile: path)
        if image == nil {
            print("missing image at: (path)")
        }
        
        return image
    }
    
    
    
    var amAktualisieren:Bool = false
    
    @IBOutlet var topScrollView: UIScrollView!
    @IBOutlet weak var imageView: UIImageView!

    override func viewDidLoad() {
        
        self.navigationController?.navigationBar.isTranslucent = false
        
        automaticallyAdjustsScrollViewInsets = false
        
        let image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
        
        if image == nil {
            
            MartinshareAPI.getStundenplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self, myImageView: imageView)
            
            imageView.image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
            
        } else {
            imageView.image = image
    
        }
        setupGestureRecognizer()
    }
    @IBAction func refresh(_ sender: AnyObject) {
        if amAktualisieren == false {
            MartinshareAPI.getStundenplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self, myImageView: imageView)
            
        }
        
    }
    
    @IBAction func backToTop(_ sender: AnyObject) {
          navigationController?.dismiss(animated: false, completion: nil)
    }

    func viewForZooming(in scrollView: UIScrollView) -> UIView? {
        return imageView
    }
    
    
    func setupGestureRecognizer() {
        let doubleTap = UITapGestureRecognizer(target: self, action: #selector(StundenplanController.handleDoubleTap(_:)))
        doubleTap.numberOfTapsRequired = 2
        topScrollView.addGestureRecognizer(doubleTap)
    }
    
    func handleDoubleTap(_ recognizer: UITapGestureRecognizer) {
        
        if (topScrollView.zoomScale > topScrollView.minimumZoomScale) {
            topScrollView.setZoomScale(topScrollView.minimumZoomScale, animated: true)
        } else {
            topScrollView.setZoomScale(2.5, animated: true)
        }
    }
    
    
    func enableUserInteraction() {
        amAktualisieren = false
        topScrollView.isUserInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        amAktualisieren = true
        topScrollView.isUserInteractionEnabled = false
    }
    
    
    func startedCon(){
        disableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.showCenteredWaitOverlay(topScrollView)
    }
    
    func conFailed() {
        enableUserInteraction()
        
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
    func wrongCredentials() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
        
        let alert = BPCompatibleAlertController(title: "Nicht Eingeloggt", message: "Bitte logge dich erneut ein.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)

        
    }
    
    func noInternetConnection() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
        
        let alert = BPCompatibleAlertController(title: "Keine Internetverbindung", message: "Bitte überprüfe deine Internetverbingung.", alertStyle: .alert)
        
        alert.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: .cancel, handler: nil))
        
        alert.presentFrom(self, animated: true, completion: nil)
        

    }
    
    func unknownError(_ string: String) {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
        
        
        let alert1 = BPCompatibleAlertController(title: "Unbekannter Fehler: \(string)", message: "Bitte probiere es erneut", alertStyle: .alert)
        alert1.addAction(BPCompatibleAlertAction(title: "Ok", actionStyle: BPCompatibleAlertActionStyle.default, handler: nil))
        alert1.presentFrom(self, animated: true, completion: nil)
    }
    
    func success() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
}
