//
//  SwiftOverlays.swift
//  SwiftTest
//
//  Created by Peter Prokop on 15/10/14.
//  Copyright (c) 2014 Peter Prokop. All rights reserved.
//

import Foundation
import UIKit


// For convenience methods
public extension UIViewController {
    func showWaitOverlay() -> UIView {
        return SwiftOverlays.showCenteredWaitOverlay(self.view)
    }
    
    func showWaitOverlayWithText(_ text: NSString) -> UIView  {
        return SwiftOverlays.showCenteredWaitOverlayWithText(self.view, text: text)
    }
    
    func showTextOverlay(_ text: NSString) -> UIView  {
        return SwiftOverlays.showTextOverlay(self.view, text: text)
    }
    
    func showImageAndTextOverlay(_ image: UIImage, text: NSString) -> UIView  {
        return SwiftOverlays.showImageAndTextOverlay(self.view, image: image, text: text)
    }
    
    class func showNotificationOnTopOfStatusBar(_ notificationView: UIView, duration: TimeInterval) {
        SwiftOverlays.showAnnoyingNotificationOnTopOfStatusBar(notificationView, duration: duration)
    }
    
    func removeAllOverlays() -> Void  {
        SwiftOverlays.removeAllOverlaysFromView(self.view)
    }
}

open class SwiftOverlays: NSObject {
    // Workaround for "Class variables not yet supported"
    // You can customize these values
    struct Statics {
        // Some random number
        static let containerViewTag = 456987123
        
        static let cornerRadius = CGFloat(10)
        static let padding = CGFloat(10)
        
        static let backgroundColor = UIColor(red: 0, green: 0, blue: 0, alpha: 0.7)
        static let textColor = UIColor(red: 1, green: 1, blue: 1, alpha: 1)
        static let font = UIFont(name: "HelveticaNeue", size: 14)!
        
        // Annoying notifications on top of status bar
        static let bannerDissapearAnimationDuration = 0.5
    }
    
    fileprivate struct PrivateStaticVars {
        static var bannerWindow : UIWindow?
    }
    
    // MARK: Public class methods
    
    open class func showCenteredWaitOverlay(_ parentView: UIView) -> UIView {
        let ai = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.whiteLarge)
        ai.startAnimating()
        
        let containerViewRect = CGRect(x: 0,
            y: 0,
            width: ai.frame.size.width * 2,
            height: ai.frame.size.height * 2)
        
        let containerView = UIView(frame: containerViewRect)
        
        containerView.tag = Statics.containerViewTag
        containerView.layer.cornerRadius = Statics.cornerRadius
        containerView.backgroundColor = Statics.backgroundColor
        containerView.center = CGPoint(x: parentView.bounds.size.width/2,
            y: parentView.bounds.size.height/2);
        
        ai.center = CGPoint(x: containerView.bounds.size.width/2,
            y: containerView.bounds.size.height/2);
        
        containerView.addSubview(ai)
        
        parentView.addSubview(containerView)
        
        return containerView
    }
    
    open class func showCenteredWaitOverlayWithText(_ parentView: UIView, text: NSString) -> UIView  {
        let ai = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)
        ai.startAnimating()
        
        return showGenericOverlay(parentView, text: text, accessoryView: ai)
    }
    
    open class func showImageAndTextOverlay(_ parentView: UIView, image: UIImage, text: NSString) -> UIView  {
        let imageView = UIImageView(image: image)
        
        return showGenericOverlay(parentView, text: text, accessoryView: imageView)
    }

    open class func showGenericOverlay(_ parentView: UIView, text: NSString, accessoryView: UIView) -> UIView {
        let label = labelForText(text)
        label.frame = label.frame.offsetBy(dx: accessoryView.frame.size.width + Statics.padding * 2, dy: Statics.padding)
        
        let actualSize = CGSize(width: accessoryView.frame.size.width + label.frame.size.width + Statics.padding * 3,
            height: max(label.frame.size.height, accessoryView.frame.size.height) + Statics.padding * 2)
        
        // Container view
        let containerViewRect = CGRect(x: 0,
            y: 0,
            width: actualSize.width,
            height: actualSize.height)
        
        let containerView = UIView(frame: containerViewRect)
        
        containerView.tag = Statics.containerViewTag
        containerView.layer.cornerRadius = Statics.cornerRadius
        containerView.backgroundColor = Statics.backgroundColor
        containerView.center = CGPoint(x: parentView.bounds.size.width/2,
            y: parentView.bounds.size.height/2);
        
        accessoryView.frame = accessoryView.frame.offsetBy(dx: Statics.padding, dy: (actualSize.height - accessoryView.frame.size.height)/2)
        
        containerView.addSubview(accessoryView)
        containerView.addSubview(label)
        
        parentView.addSubview(containerView)
        
        return containerView
    }
    
    open class func showTextOverlay(_ parentView: UIView, text: NSString) -> UIView  {
        let label = labelForText(text)
        label.frame = label.frame.offsetBy(dx: Statics.padding, dy: Statics.padding)
        
        let actualSize = CGSize(width: label.frame.size.width + Statics.padding * 2,
            height: label.frame.size.height + Statics.padding * 2)
        
        // Container view
        let containerViewRect = CGRect(x: 0,
            y: 0,
            width: actualSize.width,
            height: actualSize.height)
        
        let containerView = UIView(frame: containerViewRect)
        
        containerView.tag = Statics.containerViewTag
        containerView.layer.cornerRadius = Statics.cornerRadius
        containerView.backgroundColor = Statics.backgroundColor
        containerView.center = CGPoint(x: parentView.bounds.size.width/2,
            y: parentView.bounds.size.height/2);

        containerView.addSubview(label)
        
        parentView.addSubview(containerView)
        
        return containerView
    }
    
    open class func removeAllOverlaysFromView(_ parentView: UIView) {
        var overlay: UIView?

        while true {
            overlay = parentView.viewWithTag(Statics.containerViewTag)
            if overlay == nil {
                break
            }
            
            overlay!.removeFromSuperview()
        }
    }
    
    open class func showAnnoyingNotificationOnTopOfStatusBar(_ notificationView: UIView, duration: TimeInterval) {
        if PrivateStaticVars.bannerWindow == nil {
            PrivateStaticVars.bannerWindow = UIWindow()
            PrivateStaticVars.bannerWindow!.windowLevel = UIWindowLevelStatusBar + 1
        }
        
        PrivateStaticVars.bannerWindow!.frame = CGRect(x: 0, y: 0, width: UIScreen.main.bounds.size.width, height: notificationView.frame.size.height)
        PrivateStaticVars.bannerWindow!.isHidden = false
        
        let selector = #selector(SwiftOverlays.closeAnnoyingNotificationOnTopOfStatusBar(_:))
        let gestureRecognizer = UITapGestureRecognizer(target: self, action: selector)
        notificationView.addGestureRecognizer(gestureRecognizer)
        
        PrivateStaticVars.bannerWindow!.addSubview(notificationView)
        self.perform(selector, with: notificationView, afterDelay: duration)
    }
    
    open class func closeAnnoyingNotificationOnTopOfStatusBar(_ sender: AnyObject) {
        NSObject.cancelPreviousPerformRequests(withTarget: self)
    
        var notificationView: UIView?
        
        if sender.isKind(of: UITapGestureRecognizer) {
            notificationView = (sender as! UITapGestureRecognizer).view!
        } else if sender.isKind(of: UIView) {
            notificationView = (sender as! UIView)
        }
        
        UIView.animate(withDuration: Statics.bannerDissapearAnimationDuration,
            animations: { () -> Void in
                let frame = notificationView!.frame
                notificationView!.frame = frame.offsetBy(dx: 0, dy: -frame.size.height)
            },
            completion: { (finished) -> Void in
                notificationView!.removeFromSuperview()
                
                PrivateStaticVars.bannerWindow!.isHidden = true
            }
        )
    }
    
    // MARK: Private class methods
    
    fileprivate class func labelForText(_ text: NSString) -> UILabel {
        let textSize = text.size(attributes: [NSFontAttributeName: Statics.font])
        
        let labelRect = CGRect(x: 0,
            y: 0,
            width: textSize.width,
            height: textSize.height)
        
        let label = UILabel(frame: labelRect)
        label.font = Statics.font
        label.textColor = Statics.textColor
        label.text = text as String
        label.numberOfLines = 0
        
        return label;
    }
}
