//
//  EreignisHeaderCell.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 05.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class EreignisHeaderCell: UITableViewCell {

    @IBOutlet weak var headerTitle: UILabel!
    var headerString = ""
    
    @IBOutlet weak var firstImage: UIImageView!
    
    @IBOutlet weak var secondImage: UIImageView!
    
    @IBOutlet weak var thirdImage: UIImageView!
    
    func setTitle(_ title: String) {
        headerTitle.text = title
        headerString = title
    }
    
    func addSmallTitle(_ title: String) {
        let myMutableString = NSMutableAttributedString(string: "\(headerTitle.text!)\(title)")
        
        myMutableString.addAttribute(NSForegroundColorAttributeName, value: UIColor.black, range: NSRange(location: headerString.characters.count, length: title.characters.count))
        
        headerTitle.attributedText = myMutableString
    }
}
