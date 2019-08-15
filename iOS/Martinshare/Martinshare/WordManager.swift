//
//  SingularPluralWordManager.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 07.02.16.
//  Copyright © 2016 ModestasV Studios. All rights reserved.
//

import Foundation

class WordManager {
    
    static let arbeit = WordTemplate(sing: "Arbeit", plur: "Arbeiten")
    static let hausaufgabe = WordTemplate(sing: "Hausaufgabe", plur: "Hausaufgaben")
    static let sonstiges = WordTemplate(sing: "Sonstiges", plur: "Sonstiges")

    
}

class WordTemplate {
    let singular: String
    let plural: String
    
    init(sing: String, plur: String) {
        singular = sing
        plural = plur
    }
}

class Word {
    let template: WordTemplate
    let value: Int
    
    init(temp: WordTemplate, val: Int) {
        template = temp
        value = val
    }
    
    func get() ->String {
        if(value == 1) {
            return "\(value) \(template.singular)"
        } else if value == 0 {
            return ""
        } else {
            return "\(value) \(template.plural)"
        }
    }
    
    static func printAll(_ arr: Word...) -> String {
        var ret: String = ""
        for word in arr {
            if(word.value == 0) {
                ret += ""
            } else {
                ret += word.get()
                ret += ", "
            }
        }

        let retu = String(ret.characters.dropLast(2))
        return retu
    }
}
