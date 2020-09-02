import React from 'react'
import {Link} from "react-router-dom";

class Logout extends React.Component {
    render() {
        return (
            <div className="card mb-4">
                <div className="card-content">
                    <Link to={'/logout'} className="button is-info">
                        Log out
                    </Link>
                </div>
            </div>
        )
    }
}

export default Logout
