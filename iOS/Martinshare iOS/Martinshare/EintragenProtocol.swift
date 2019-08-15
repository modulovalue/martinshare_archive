
//
//  EintragenProtocol.swift
//  Martinshare
//
//  Created by Modestas Valauskas on 30.05.15.
//  Copyright (c) 2015 ModestasV Studios. All rights reserved.
//

import Foundation

protocol EintragenProtocol {
    
    func startedConnection()
    func notLoggedIn()
    //Ich werde die Eintraege aktualisieren und dem nutzer anzeigen "BITTE VERSUCHE ES SPÄTER NOCHMAL"
    func unknownError()
    func noInternet()
    func aktualisiert()
}