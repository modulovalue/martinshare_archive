//
//  BPCompatibleAlertController.swift
//  RelSci
//
//  Created by Bay Phillips on 12/1/14.
//  Copyright (c) 2014 Relationship Science LLC. All rights reserved.
//

import Foundation
import UIKit
fileprivate func < <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l < r
  case (nil, _?):
    return true
  default:
    return false
  }
}

fileprivate func > <T : Comparable>(lhs: T?, rhs: T?) -> Bool {
  switch (lhs, rhs) {
  case let (l?, r?):
    return l > r
  default:
    return rhs < lhs
  }
}


public typealias BPCompatibleTextFieldConfigruationHandler = ((UITextField?) -> Void)!

public enum BPCompatibleAlertControllerStyle {
    case actionsheet
    case alert
}


protocol AlertProtocol {
    
}

@available(iOS 8.0, *)
extension BPCompatibleAlertController {
    var alertControllerStyle: UIAlertControllerStyle {
        get {
            return alertStyle == BPCompatibleAlertControllerStyle.actionsheet
                ? UIAlertControllerStyle.actionSheet
                : UIAlertControllerStyle.alert
        }
    }
}

@available(iOS 8.0, *)
private class BPAlertController: UIAlertController, AlertProtocol {

}

private class BPAlertView : UIAlertView, AlertProtocol {
    // Keep a strong reference to the delegate to ensure the BPCompatibleAlertController doesn't get destroyed by ARC on iOS 7
    // Once the alert button has been clicked and BPCompatibleAlertController is finished with this alert view, BPCompatibleAlertController
    //  can clear the delegateReference and ARC can clean up
    var delegateReference: BPCompatibleAlertController?
    
    override init(frame: CGRect) {
        super.init(frame: frame)
    }

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
    }
    
    init(title: String?, message: String?, delegate: BPCompatibleAlertController, cancelButtonTitle: String?) {
        delegateReference = delegate
        super.init(frame: CGRect.zero)
        self.title = title ?? ""
        self.message = message
        self.delegate = delegate
        //super.init(title: title, message: message, delegate: delegate, cancelButtonTitle: cancelButtonTitle)
    }
}

open class BPCompatibleAlertController : NSObject, UIAlertViewDelegate {
    let title: String?
    let message: String?
    let alertStyle: BPCompatibleAlertControllerStyle
    
    /**
    Allows for UITextFields in iOS7 UIAlertViews. Must have corresponding counts of
    BPCompatibleTextFieldConfigurationHandlers for each style.
    Default = 0
    PlainTextInput or SecureTextInput = 1
    LoginAndPasswordInpnut = 2
    */
    open var alertViewStyle: UIAlertViewStyle
    
    /** 
    Client code can optionally set this if they have custom clean up code they want run after an alert action has been triggered.
    
    This cleanup function will be run regardless of whether or not the user provided an action handler block with an action.
    If the user did provide an action handler block, this cleanup function will be invoked after the user supplied handler.
    */
    open var postActionHandlerCleanupFunction: ((Void) -> Void)?
    
    /**
    If this set, when the user dismisses the alert all the resources used
    by this alert controller will be automatically released allowing this object to be deallocated.
    
    After the user has dismissed the alert, presentFrom: cannot be called again on this object.
    
    Should client code set this to false (because they intend to re-user the alert), it must at some stage call releaseResources() to
    ensure internal resources are freed up.
    
    releaseResourcesWhenAlertDismissed defaults to true.
    */
    open var releaseResourcesWhenAlertDismissed: Bool = true
    
    fileprivate var alerter: AlertProtocol!
    fileprivate var actions: [String : BPCompatibleAlertAction]
    fileprivate var actionObservers: [NSObjectProtocol] = []
    open var resourcesHaveBeenReleased: Bool = false
    fileprivate var textFieldConfigurations: [BPCompatibleTextFieldConfigruationHandler]
    
    fileprivate var uiAlertControllerAvailable: Bool {
        get {
            return objc_getClass("UIAlertController") != nil
        }
    }
    
    /**
    Creates an instance of BPCompatibleAlertController.
    
    - parameter title: The title of the alert shown.
    - parameter message: The message shown for the alert below the title.
    - parameter alertStyle: The style to be used when displaying the alert. Currently only supporting iOS 8.
    
    - returns: The created alert controller.
    */
    public init(title: String?, message: String?, alertStyle: BPCompatibleAlertControllerStyle) {
        self.title = title
        self.message = message
        self.alertStyle = alertStyle
        self.alertViewStyle = UIAlertViewStyle.default
        self.actions = [String : BPCompatibleAlertAction]()
        self.textFieldConfigurations = [BPCompatibleTextFieldConfigruationHandler]()
    }
    
    /**
    Creates a BPCompatibleAlertController with a type of Alert.
    
    - parameter title: The title of the alert shown.
    - parameter message: The message shown for the alert below the title.
    
    :   returns: The created alert controller.
    */
    class func alertControllerWithTitle(_ title: String?, message: String?) -> BPCompatibleAlertController {
        return BPCompatibleAlertController(title: title, message: message, alertStyle: BPCompatibleAlertControllerStyle.alert)
    }
    
    /**
    Creates a BPCompatibleAlertController with a type of ActionSheet.
    
    - parameter title: The title of the alert shown.
    - parameter message: The message shown for the alert below the title.
    
    - returns: The created alert controller.
    */
    class func actionSheetControllerWithTitle(_ title: String?, message: String?) -> BPCompatibleAlertController {
        return BPCompatibleAlertController(title: title, message: message, alertStyle: BPCompatibleAlertControllerStyle.actionsheet)
    }
    
    /**
    Adds a button with a corresponding action to be executed upon pressing.
    
    - parameter action: The BPCompatibleAlertAction with set title and action block.
    */
    open func addAction(_ action: BPCompatibleAlertAction) -> Void {
        actions[action.title!] = action
        
        // listen to changes on the BPCompatibleAlertAction enabled field to update the UIAlertAction in the alertController
        let observerObject = NotificationCenter.default.addObserver(forName: NSNotification.Name(rawValue: BPCompatibleAlertActionEnabledDidChangeNotification), object: action, queue: OperationQueue.main) { (notification) in
            //if #ava ilabl     e(iO S 8.10, *) {
                if let alertController = self.alerter as? BPAlertController {
                    for alertAction in alertController.actions {
                        if alertAction.title == action.title {
                            alertAction.isEnabled = action.enabled
                            break
                        }
                    }
                }
            //}
        }
        self.actionObservers.append(observerObject)
    }
    
    open func addTextFieldWithConfigurationHandler(_ configurationHandler : BPCompatibleTextFieldConfigruationHandler ) -> Void {
        textFieldConfigurations.append(configurationHandler)
    }
    
    /**
    Presents the BPCompatibleAlertController to the user in the passed in UIViewController. If iOS 7, will
    disregard the viewController and simply show the UIAlertView.
    
    - parameter viewController: The UIViewController for the UIAlertController to be displayed to. Not used in iOS 7.
    - parameter animated: Whether or not to animate the presentation.
    - parameter completion: The completion block to be called when done presenting.
    */
    open func presentFrom(_ viewController: UIViewController!, animated: Bool, completion: (() -> Void)?) {
        assert(resourcesHaveBeenReleased == false, "You cannot present an alert controller again after its resources have been released!")
        
       // if #available(iOS 8.0, *) {
            self.alerter = BPAlertController(title: title, message: message, preferredStyle: alertControllerStyle)
            let alertController = self.alerter as! BPAlertController
            for action in actions.values {
                
                let uiAlertAction = UIAlertAction(title: action.title!, style: action.alertActionStyle, handler: { (alertAction) in
                    if let handler = action.handler {
                        handler(action)
                    }
                    
                    self.postAlertDismissalActions()
                })
                uiAlertAction.isEnabled = action.enabled
                alertController.addAction(uiAlertAction)
            }
            
            for textFieldHandler in textFieldConfigurations {
                alertController.addTextField(configurationHandler: textFieldHandler)
            }
            
            viewController.present(alertController, animated: animated, completion: { () in
                if let completionHandler = completion {
                    completionHandler()
                }
            })
//        } else {
//            var actionsCopy:[String : BPCompatibleAlertAction] = actions
//            var cancelAction: BPCompatibleAlertAction?
//            var index = 0
//            
//            for (title, action) in actions {
//                if action.actionStyle == BPCompatibleAlertActionStyle.Cancel {
//                    cancelAction = action
//                    actionsCopy.removeValueForKey(title)
//                    break
//                }
//                index++
//            }
//            
//            self.alerter = BPAlertView(title: title, message: message, delegate: self, cancelButtonTitle: cancelAction?.title)
//            let alertView = self.alerter as! BPAlertView
//            sanityCheckTextFields()
//            
//            alertView.alertViewStyle = alertViewStyle
//            
//            for (index, config) in textFieldConfigurations.enumerate() {
//                let textField = alertView.textFieldAtIndex(index) as UITextField!
//                config(textField)
//            }
//            
//            for (title, _) in actionsCopy {
//                alertView.addButtonWithTitle(title)
//            }
//            
//            alertView.show()
//        }
    }
    
    fileprivate func sanityCheckTextFields() {
        if alertViewStyle == UIAlertViewStyle.default {
            assert(textFieldConfigurations.isEmpty, "You must set the alertViewStyle property to the corresponding style in order to use textFields in iOS7")
        } else if alertViewStyle == UIAlertViewStyle.plainTextInput || alertViewStyle == UIAlertViewStyle.secureTextInput {
            assert(textFieldConfigurations.count == 1, "You must specify only 1 textField when using either SecureTextInput or PlainTextInput")
        } else if alertViewStyle == UIAlertViewStyle.loginAndPasswordInput {
            assert(textFieldConfigurations.count == 2, "You must specify 2 textFields when using the LoginAndPasswordInput alertViewStyle")
        }
    }
    
    open func textFieldAtIndex(_ index: Int) -> UITextField? {
        
       
        
            let alertController = self.alerter as! BPAlertController
            if alertController.textFields?.count > index {
                if let textField: UITextField = alertController.textFields![index] as UITextField {
                    return textField
                }
            }
            return nil
//        } else {
//            let alertView = self.alerter as! BPAlertView
//            return alertView.textFieldAtIndex(index)
//        }
    }
    
    /**
    Used to handle iOS 7 UIAlertView delegate calls. Do not use.
    */
    open func alertView(_ alertView: UIAlertView, clickedButtonAt buttonIndex: Int) {
        
        // Use optional action, as this delegate function can be called multiple times if user repeatedly taps
        //  button quickly. (Hard to do, but possible during view controller transitions...)
        // After the first invocation postAlertDismissalActions() will have already removed
        //  all the actions, resulting in a nil action.
        let alertView = self.alerter as! BPAlertView
        let action = actions[alertView.buttonTitle(at: buttonIndex)!] as BPCompatibleAlertAction?
        if let handler = action?.handler {
            handler(action)
        }
        
        postAlertDismissalActions()
    }
    
    /**
    If the client code has opted to set releaseResourcesWhenAlertDismissed to false, then 
    this function should be called manually at an appropriate time to free up internal resources
    and allow this object to be deallocated.
    */
    open func releaseResources() {
        // Removing observers releases any references to self held by the notification handler block, allowing self to be deallocated
        stopObservingAlertActionEnabledDidChangeNotification()
        
        // Clean up any other objects that may be containining references to self and prevent self from being deallocated
        actions.removeAll(keepingCapacity: false)
        textFieldConfigurations.removeAll(keepingCapacity: false)
        
        if let alertView = self.alerter as? BPAlertView {
            alertView.delegate = nil
            alertView.delegateReference = nil
        }
        self.alerter = nil

        resourcesHaveBeenReleased = true
    }
    
    fileprivate func postAlertDismissalActions() {
        if (releaseResourcesWhenAlertDismissed) {
            releaseResources()
        }
        
        if (postActionHandlerCleanupFunction != nil) {
            postActionHandlerCleanupFunction?()
        }
    }
    
    fileprivate func stopObservingAlertActionEnabledDidChangeNotification() {
        for actionObserver in actionObservers {
            NotificationCenter.default.removeObserver(actionObserver)
        }
        self.actionObservers.removeAll(keepingCapacity: false)
    }
}
