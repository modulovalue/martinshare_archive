//
//  DecodeHTML.swift
//
//  Created by TheFlow_ on 01/03/2015.
//  Copyright (c) 2015 TheFlow_. All rights reserved.
//

import Foundation
import UIKit

extension String {
    var htmlToString:String {
        
        let nocrlf = self.replacingOccurrences(of: "\n", with: "<br>")
        
        let converted = try! NSAttributedString(
            data: nocrlf.data(using: String.Encoding.utf8)!,
            options: [NSDocumentTypeDocumentAttribute: NSHTMLTextDocumentType, NSCharacterEncodingDocumentAttribute:String.Encoding.utf8],
            documentAttributes: nil).string
        
        return converted
        
    }
}
