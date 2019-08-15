//
//  UIImageExtension.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 17.11.15.
//  Copyright © 2015 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class UIImageExtension {
    
    internal static func combine(_ image1: UIImage, image2: UIImage) -> UIImage {
        
        let size: CGSize = CGSize(width: image1.size.width + image2.size.width, height: image1.size.height);
        
        UIGraphicsBeginImageContextWithOptions(size, false, UIScreen.main.scale);
        
        image1.draw(in: CGRect(x: 0, y: 0, width: image1.size.width, height: image1.size.height))
        
        image2.draw(in: CGRect(x: image1.size.width, y: 0, width: image2.size.width, height: image2.size.height))
        
        let finalImage = UIGraphicsGetImageFromCurrentImageContext();
        
        UIGraphicsEndImageContext()
        return finalImage!
    
    }
}
