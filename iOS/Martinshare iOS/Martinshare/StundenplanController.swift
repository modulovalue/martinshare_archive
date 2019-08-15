//
//  StundenplanController.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 26.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//
import UIKit




class StundenplanController : UIViewController, UIScrollViewDelegate, ConnectionProtocol {
   
    // Get the documents Directory
    static func documentsDirectory() -> String {
        let documentsFolderPath = NSSearchPathForDirectoriesInDomains(NSSearchPathDirectory.DocumentDirectory, NSSearchPathDomainMask.UserDomainMask, true)[0] as! String
        return documentsFolderPath
    }
    
    // Get path for a file in the directory
    static func fileInDocumentsDirectory(filename: String) -> String {
        return documentsDirectory().stringByAppendingPathComponent(filename)
    }
    
    // Define the specific path, image name
    // static let imagePath = self.fileInDocumentsDirectory(MartinshareAPI.stundenplanName)
    static func saveImage (image: UIImage, path: String ) -> Bool{
        let pngImageData = UIImagePNGRepresentation(image)
        //let jpgImageData = UIImageJPEGRepresentation(image, 1.0)   // if you want to save as JPEG
        let result = pngImageData.writeToFile(path, atomically: true)
        return result
    }
    
    static func loadImageFromPath(path: String) -> UIImage? {
        let image = UIImage(contentsOfFile: path)
        if image == nil {
            println("missing image at: (path)")
        }
        
        println("\(path)") // this is just for you to see the path in case you want to go to the directory, using Finder.
        return image
    }
    
    
    
    var amAktualisieren:Bool = false
    
    @IBOutlet var topScrollView: UIScrollView!
    @IBOutlet weak var imageView: UIImageView!

    override func viewDidLoad() {
        
        self.navigationController?.navigationBar.translucent = false
        
        automaticallyAdjustsScrollViewInsets = false
        
        var image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
        
        if image == nil {
            
            MartinshareAPI.getStundenplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self, myImageView: imageView)
            
            imageView.image = StundenplanController.loadImageFromPath(StundenplanController.fileInDocumentsDirectory(MartinshareAPI.stundenplanName))
            
        } else {
            imageView.image = image
    
        }
        
    }
    @IBAction func refresh(sender: AnyObject) {
        if amAktualisieren == false {
            MartinshareAPI.getStundenplan(Prefs.getGoodUsername(), key: Prefs.getKey(), conPro: self, myImageView: imageView)
            
        }
        
    }
    

    func viewForZoomingInScrollView(scrollView: UIScrollView) -> UIView? {
        return imageView
    }
    
    
    
    func enableUserInteraction() {
        amAktualisieren = false
        topScrollView.userInteractionEnabled = true
    }
    
    func disableUserInteraction() {
        amAktualisieren = true
        topScrollView.userInteractionEnabled = false
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
    }
    
    func noInternetConnection() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
    func unknownError() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
    func success() {
        enableUserInteraction()
        topScrollView.zoomScale = 1
        SwiftOverlays.removeAllOverlaysFromView(topScrollView)
    }
    
}