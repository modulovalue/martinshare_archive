//
//  EreignisCell.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 05.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation
import UIKit

class EreignisCell: UITableViewCell {

    @IBOutlet weak var title: UILabel!
    @IBOutlet weak var subtitle: UILabel!
    @IBOutlet weak var date: UILabel!
    
    func setSubtitleCheck(_ str: String) {
        if(str.isEmpty) {
            subtitle.text = "Keine Beschreibung vorhanden"
        } else {
            subtitle.text = str.htmlToString
        }
    }
}
