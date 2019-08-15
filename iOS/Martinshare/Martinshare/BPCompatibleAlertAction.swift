//
//  BPCompatibleAlertAction.swift
//  RelSci
//
//  Created by Bay Phillips on 12/1/14.
//  Copyright (c) 2014 Relationship Science LLC. All rights reserved.
//

import Foundation
import UIKit

public enum BPCompatibleAlertActionStyle {
    case `default`
    case cancel
    case destructive
}

let BPCompatibleAlertActionEnabledDidChangeNotification: String = "BPCompatibleAlertActionEnabledDidChangeNotification"

@available(iOS 8.0, *)
public extension BPCompatibleAlertAction
{
    /**
    The UIAlertActionStyle to be used for the UIAlertController in iOS 8.
    */
    public var alertActionStyle: UIAlertActionStyle {
        get {
            if self.actionStyle == BPCompatibleAlertActionStyle.cancel {
                return UIAlertActionStyle.cancel
            } else if self.actionStyle == BPCompatibleAlertActionStyle.destructive {
                return UIAlertActionStyle.destructive
            } else {
                return UIAlertActionStyle.default
            }
        }
    }
}

open class BPCompatibleAlertAction {
    /**
    The title of the Action to be shown in the button.
    */
    open let title: String?
    
    /**
    The style of the button, depending on its type of action.
    */
    open let actionStyle: BPCompatibleAlertActionStyle
    
    /**
    The handler to be called when the action/button is pressed.
    */
    open let handler: ((BPCompatibleAlertAction?) -> Void)?
    
    /**
    Whether or not this action is actionable.
    */
    open var enabled: Bool {
        didSet {
            if (enabled != oldValue) {
                // let the controller
                NotificationCenter.default.post(name: Notification.Name(rawValue: BPCompatibleAlertActionEnabledDidChangeNotification), object: self)
            }
        }
    }

    
    /**
    Creates an instance of BPCompatibleAlertAction.
    
    - parameter title: The title of the button.
    - parameter actionStyle: The style of the button.
    - parameter handler: The block to be called when the button is pressed.
    
    - returns: The created action.
    */
    public init(title: String?, actionStyle: BPCompatibleAlertActionStyle, handler: ((BPCompatibleAlertAction?) -> Void)?) {
        self.title = title
        self.actionStyle = actionStyle
        self.handler = handler
        self.enabled = true
    }
    
    
    /**
    Helper function to create an instance of BPCompatibleAlertAction with the type of Default.
    
    - parameter title: The title of the button.
    - parameter handler: The block to be called when the button is pressed.
    
    - returns: The created action.
    */
    class func defaultActionWithTitle(_ title: String?, handler: ((BPCompatibleAlertAction?) -> Void)?) -> BPCompatibleAlertAction {
        return BPCompatibleAlertAction(title: title, actionStyle: BPCompatibleAlertActionStyle.default, handler: handler)
    }
    
    /**
    Helper function to create an instance of BPCompatibleAlertAction with the type of Cancel.
    
    - parameter title: The title of the button.
    - parameter handler: The block to be called when the button is pressed.
    
    - returns: The created action.
    */
    class func cancelActionWithTitle(_ title: String?, handler: ((BPCompatibleAlertAction?) -> Void)?) -> BPCompatibleAlertAction {
        return BPCompatibleAlertAction(title: title, actionStyle: BPCompatibleAlertActionStyle.cancel, handler: handler)
    }
    
    /**
    Helper function to create an instance of BPCompatibleAlertAction with the type of Desctructive.
    
    - parameter title: The title of the button.
    - parameter handler: The block to be called when the button is pressed.
    
    - returns: The created action.
    */
    class func destructiveActionWithTItle(_ title: String?, handler: ((BPCompatibleAlertAction?) -> Void)?) -> BPCompatibleAlertAction {
        return BPCompatibleAlertAction(title: title, actionStyle: BPCompatibleAlertActionStyle.destructive, handler: handler)
    }
}
