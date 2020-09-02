import React from 'react'
import axios from 'axios'

class Listing extends React.Component {
    constructor(props) {
        super(props);

        this.handleClick = this.handleClick.bind(this)
    }

    handleClick(e) {
        e.preventDefault()

        const {id} = this.props.listing

        if (this.props.removingListings) {
            if (id === -1) {
                return null
            }
            this.props.removeListing(this.props.listing)
        } else {
            this.props.setCurrentListingId(id)
        }
    }

    render() {
        const {name} = this.props.listing

        return (
            <a href="" className="panel-block" onClick={this.handleClick}>
                <span className="panel-icon">
                  <i className="fas fa-book" aria-hidden="true"/>
                </span>
                {name}
            </a>
        )
    }
}

export default Listing
